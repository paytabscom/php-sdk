<?php

namespace Http;

use CurlHandle;
use Logger\LoggerInterface;
use Request\RequestInterface;
use Response\Response;

class Http
{
    private int $timeout = 30;

    private RequestInterface $request;

    private LoggerInterface $logger;

    //

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    public function setRequest(RequestInterface $request)
    {
        $this->request = $request;
    }

    public function submit(): Response
    {
        $curl_handle = $this->initRequest();

        $this->logger->debug('Executing cURL ...', null);

        $curl_response = curl_exec($curl_handle);
        $errorNo = curl_errno($curl_handle);
        if ($errorNo) {
            $errorMsg = curl_error($curl_handle);

            $this->logger->error($errorMsg, null);
        }

        curl_close($curl_handle);

        $response = new Response;
        $response->setResponse($curl_response);

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

            CURLOPT_VERBOSE => true,
        ];

        $curl_http_type = [
            CURLOPT_POST => $this->request->isHttpPost(),
        ];

        $arr =
            $curl_options_ssl
            + $curl_options_response
            + $curl_http_type
            + [
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_POSTFIELDS => $payload,
            ];

        curl_setopt_array(
            $curl,
            $arr
        );

        return $curl;
    }
}
