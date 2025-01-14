<?php

namespace Paytabs\Sdk\Http;

use CurlHandle;
use Exception;
use Paytabs\Sdk\Request\RequestInterface;
use Paytabs\Sdk\Response\Response;
use Paytabs\Sdk\Response\ResponseInterface;
use Psr\Log\LoggerInterface;

class Http
{
    protected RequestInterface $request;
    protected LoggerInterface $logger;

    private int $timeout = 30;
    private bool $debugMode = false;

    //

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    public function setRequest(RequestInterface $request)
    {
        $this->request = $request;
    }

    public function setDebugMode(bool $debugMode)
    {
        $this->debugMode = $debugMode;
    }

    public function submit(?ResponseInterface $response = null): ResponseInterface
    {
        $curl_handle = $this->initRequest();

        $this->logger->debug('Executing cURL ...', []);

        $curl_response = curl_exec($curl_handle);
        $curl_response_code = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);

        $errorNo = curl_errno($curl_handle);
        if ($errorNo) {
            $errorMsg = curl_error($curl_handle);

            $this->logger->error('cURL failed: ', [$errorMsg]);

            throw new Exception($errorMsg);
        }

        curl_close($curl_handle);

        $response = $response ?? new Response();

        $response->init($curl_response, $curl_response_code, $this->request);

        return $response;
    }

    //

    private function initRequest(): CurlHandle
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

        $curl_data =  [
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $payload,
        ];

        $arr =
            $curl_options_ssl
            + $curl_options_response
            + $curl_http_type
            + $curl_data;

        curl_setopt_array(
            $curl,
            $arr
        );

        return $curl;
    }
}
