<?php

namespace Response;

use Enums\ResponseType;

class Response implements ResponseInterface
{
    protected int $responseCode;
    private string $response;

    //

    public function __construct(string $response, int $responseCode)
    {
        $this->responseCode = $responseCode;
        $this->response = $response;
    }

    public function getResponse(): string
    {
        return $this->response;
    }

    public function getJson()
    {
        return json_decode($this->getResponse());
    }

    //

    public function responseType(): ResponseType
    {
        $response_decoded = json_decode($this->response);

        if (
            isset($response_decoded->tran_ref, $response_decoded->redirect_url)
            && !empty($response_decoded->redirect_url)
        ) {
            return ResponseType::Redirect;
        }

        if (isset($response_decoded->code)) {
            return ResponseType::Error;
        }

        if (isset($response_decoded->payment_result)) {
            return ResponseType::Completed;
        }

        return ResponseType::UnKnown;
    }
}
