<?php

namespace Response;

use Enums\ResponseStage;
use JsonMapper;
use Request\RequestInterface;

class Response implements ResponseInterface
{
    protected RequestInterface $request;

    protected int $responseCode;
    private string $response;

    //

    public function __construct(string $response, int $responseCode, RequestInterface $request)
    {
        $this->responseCode = $responseCode;
        $this->response = $response;

        $this->request = $request;
    }

    public function getRaw(): string
    {
        return $this->response;
    }

    public function getJson()
    {
        return json_decode($this->getRaw());
    }

    public function getResponse(?string $responseClass = null)
    {
        $mapToClass = null;
        $isArray = false;

        if ($responseClass != null) {
            $mapToClass = $responseClass;
        } elseif ($this->request->getResponseClass() != null) {
            $mapToClass = $this->request->getResponseClass();
        }

        if ($mapToClass != null) {
            $jsonMapper = new JsonMapper();
            if ($isArray) {
                // return $jsonMapper->mapArray($this->getJson(),)
            } else {
                return $jsonMapper->map($this->getJson(), $mapToClass);
            }
        }

        return $this->getJson();
    }

    //

    public function responseStage(): ResponseStage
    {
        $response_decoded = json_decode($this->response);

        // "Delete Token" request returns same structure but code=0
        if (isset($response_decoded->code) && $response_decoded->code > 0) {
            return ResponseStage::Error;
        }

        if (
            isset($response_decoded->tran_ref, $response_decoded->redirect_url)
            && !empty($response_decoded->redirect_url)
        ) {
            return ResponseStage::Redirect;
        }

        if (isset($response_decoded->payment_result)) {
            return ResponseStage::Completed;
        }

        return ResponseStage::UnKnown;
    }
}
