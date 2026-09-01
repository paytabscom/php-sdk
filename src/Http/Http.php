<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Http;

use Paytabs\Sdk\Exceptions\HttpRequestException;
use Paytabs\Sdk\Helpers\Helpers;
use Paytabs\Sdk\Logger\Redactor;
use Paytabs\Sdk\Request\RequestInterface;
use Paytabs\Sdk\Response\Payload\Payloads\Generic as PayloadsGeneric;
use Paytabs\Sdk\Response\ResponseDirectInterface;
use Paytabs\Sdk\Response\Responses\Direct\Generic;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Http
{
    protected RequestInterface $request;
    protected LoggerInterface $logger;

    private int $timeout = 30;
    private int $connectTimeout = 10;
    private bool $debugMode = false;

    /** @var null|resource */
    private $debugStream;

    public function __construct()
    {
        $this->logger = new NullLogger();
    }

    public static function create(?RequestInterface $request = null): self
    {
        $instance = new self();

        if ($request) {
            $instance->setRequest($request);
        }

        return $instance;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    public function setRequest(RequestInterface $request)
    {
        if (!$request->isReady()) {
            throw new \InvalidArgumentException('Request is not Ready.');
        }
        $this->request = $request;

        return $this;
    }

    public function setDebugMode(bool $debugMode)
    {
        $this->debugMode = $debugMode;

        return $this;
    }

    public function submit(?ResponseDirectInterface $responseClass = null): ResponseDirectInterface
    {
        $curl_handle = $this->initRequest();

        $this->logger->debug('Executing cURL ...', []);

        try {
            $requestResult = $this->executeRequest($curl_handle);
        } finally {
            // Always emit the verbose trace, including on a transport failure —
            // that is exactly when it is worth having.
            $this->flushDebugMode();
        }

        $curl_response = $requestResult['response'];
        $curl_response_code = $requestResult['statusCode'];
        $errorNo = $requestResult['errorNo'];

        if ($errorNo) {
            $errorMsg = $requestResult['errorMessage'];

            $this->logger->error('cURL failed: ', [$errorMsg]);

            throw HttpRequestException::transport($errorMsg, $errorNo);
        }

        $isSuccessStatus = $curl_response_code >= 200 && $curl_response_code < 300;

        if (!$isSuccessStatus) {
            // Keep non-2xx payloads available for response-layer classification
            // — the gateway returns structured JSON errors.
            if (false === $curl_response || '' === $curl_response) {
                throw HttpRequestException::invalidStatusCode($curl_response_code);
            }

            // But a non-JSON body (an HTML error page from a CDN or WAF, the
            // common real-world case) must be reported here, with the status and
            // body preserved.
            if (\is_string($curl_response) && !Helpers::jsonValidate($curl_response)) {
                $this->logger->error('Non-2xx response with a non-JSON body', [
                    'status' => $curl_response_code,
                ]);

                throw HttpRequestException::unexpectedResponseBody($curl_response_code, $curl_response);
            }
        }

        if (false === $curl_response) {
            throw HttpRequestException::transport('empty response', $curl_response_code);
        }

        if (!$responseClass) {
            $responseClass = new Generic();
        }

        if (!$responseClass->getPayload()) {
            if ($this->request->getResponseClass()) {
                $responseClass->setPayload($this->request->getResponseClass());
            } else {
                $responseClass->setPayload(new PayloadsGeneric());
            }
        }

        $responseClass
            ->setResponse($curl_response)
            ->setResponseCode($curl_response_code)
            ->setRequest($this->request)
        ;

        return $responseClass;
    }

    /**
     * Routes cURL's verbose trace through the PSR-3 logger with the
     * Authorization header redacted.
     *
     * CURLOPT_VERBOSE alone writes the full request headers — including
     * `Authorization: <serverKey>` — to stderr and the web-server error log,
     * with no redaction and no way to intercept it.
     */
    protected function applyDebugMode(\CurlHandle $curl): void
    {
        if (!$this->debugMode) {
            return;
        }

        $stream = fopen('php://temp', 'w+b');

        if (false === $stream) {
            // Debugging is best-effort; never fail a payment over it. Verbose
            // output is deliberately left off rather than allowed to go to
            // stderr unredacted.
            $this->logger->warning('Debug mode requested but no buffer could be opened; verbose output disabled.');

            return;
        }

        $this->debugStream = $stream;

        curl_setopt($curl, CURLOPT_VERBOSE, true);
        curl_setopt($curl, CURLOPT_STDERR, $stream);
    }

    /**
     * Emits and closes any buffered verbose trace.
     */
    protected function flushDebugMode(): void
    {
        if (!\is_resource($this->debugStream)) {
            return;
        }

        rewind($this->debugStream);
        $trace = stream_get_contents($this->debugStream) ?: '';
        fclose($this->debugStream);
        $this->debugStream = null;

        $redacted = implode(
            PHP_EOL,
            array_map(
                static fn(string $line): string => Redactor::headerLine($line),
                explode("\n", $trace)
            )
        );

        $this->logger->debug('cURL verbose trace', ['trace' => $redacted]);
    }

    /**
     * @return array{response: array|false|string, statusCode: int, errorNo: int, errorMessage: string}
     */
    protected function executeRequest(\CurlHandle $curl_handle): array
    {
        return [
            'response' => curl_exec($curl_handle),
            'statusCode' => (int) curl_getinfo($curl_handle, CURLINFO_HTTP_CODE),
            'errorNo' => curl_errno($curl_handle),
            'errorMessage' => curl_error($curl_handle),
        ];
    }

    private function initRequest(): \CurlHandle
    {
        if (!isset($this->request)) {
            throw new \RuntimeException('Request is not set.');
        }

        $url = $this->request->getUrl();
        $headers = $this->request->getHeaders();
        $isPost = $this->request->isHttpPost();

        $curl = curl_init($url);

        if (false === $curl) {
            throw HttpRequestException::transport('could not initialise a cURL handle');
        }

        $curl_options_ssl = [
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ];

        $curl_options_response = [
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_CONNECTTIMEOUT => $this->connectTimeout,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
        ];

        $curl_data = [
            CURLOPT_HTTPHEADER => $headers,
        ];

        if ($isPost) {
            $curl_data[CURLOPT_POST] = true;
            $curl_data[CURLOPT_POSTFIELDS] = $this->request->getPayload();
        } else {
            // Do not build a body for GET. CURLOPT_POSTFIELDS flips the method
            // to POST and CURLOPT_HTTPGET then flips it back, silently dropping
            // whatever was built.
            $curl_data[CURLOPT_HTTPGET] = true;
        }

        $arr
            = $curl_options_ssl
            + $curl_options_response
            + $curl_data;

        curl_setopt_array(
            $curl,
            $arr
        );

        $this->applyDebugMode($curl);

        return $curl;
    }
}
