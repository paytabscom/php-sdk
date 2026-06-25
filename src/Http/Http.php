<?php

namespace Paytabs\Sdk\Http;

use Paytabs\Sdk\Exceptions\HttpRequestException;
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
    private bool $debugMode = false;

    public function __construct()
    {
        $this->logger = new NullLogger();
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    public function setRequest(RequestInterface $request)
    {
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

        $requestResult = $this->executeRequest($curl_handle);

        $curl_response = $requestResult['response'];
        $curl_response_code = $requestResult['statusCode'];
        $errorNo = $requestResult['errorNo'];

        if ($errorNo) {
            $errorMsg = $requestResult['errorMessage'];

            $this->logger->error('cURL failed: ', [$errorMsg]);

            throw HttpRequestException::transport($errorMsg, $errorNo);
        }

        // Keep non-2xx payloads available for response-layer classification.
        // Throw only when status is non-2xx and there is no response body to parse.
        if (!($curl_response_code >= 200 && $curl_response_code < 300)
            && (false === $curl_response || '' === $curl_response)
        ) {
            throw HttpRequestException::invalidStatusCode($curl_response_code);
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
        $url = $this->request->getUrl();
        $payload = $this->request->getPayload();
        $headers = $this->request->getHeaders();

        $curl = curl_init($url);

        $curl_options_ssl = [
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ];

        $curl_options_response = [
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,

            CURLOPT_VERBOSE => $this->debugMode,
        ];

        $curl_http_type = [
            CURLOPT_POST => $this->request->isHttpPost(),
        ];

        $curl_data = [
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $payload,
        ];

        $arr
            = $curl_options_ssl
            + $curl_options_response
            + $curl_http_type
            + $curl_data;

        curl_setopt_array(
            $curl,
            $arr
        );

        // Force set GET request type
        if (!$this->request->isHttpPost()) {
            curl_setopt($curl, CURLOPT_HTTPGET, 1);
        }

        return $curl;
    }
}
