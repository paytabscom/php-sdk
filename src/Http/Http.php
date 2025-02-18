<?php

namespace Paytabs\Sdk\Http;

use Exception;
use Paytabs\Sdk\Request\RequestInterface;
use Paytabs\Sdk\Response\Payloads\Generic as PayloadsGeneric;
use Paytabs\Sdk\Response\ResponseDirectInterface;
use Paytabs\Sdk\Response\Responses\Direct\Generic;
use Psr\Log\LoggerInterface;

class Http
{
    protected RequestInterface $request;
    protected LoggerInterface $logger;

    private int $timeout = 30;
    private bool $debugMode = false;

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

    public function submit(?ResponseDirectInterface $responseClass = null): ResponseDirectInterface
    {
        $curl_handle = $this->initRequest();

        $this->logger->debug('Executing cURL ...', [$this->request->getUrl()]);

        $curl_response = curl_exec($curl_handle);
        $curl_response_code = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);

        $errorNo = curl_errno($curl_handle);
        if ($errorNo) {
            $errorMsg = curl_error($curl_handle);

            $this->logger->error('cURL failed: ', [$errorMsg]);

            curl_close($curl_handle);

            throw new \Exception($errorMsg);
        }

        curl_close($curl_handle);

        if (!($curl_response_code >= 200 && $curl_response_code < 300)) {
            // throw new Exception('Invalid Request');
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

    private function initRequest()
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

        $arr =
            $curl_options_ssl
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
