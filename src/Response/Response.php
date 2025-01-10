<?php

namespace Paytabs\Sdk\Response;

use Enums\ResponseStage;
use Request\RequestInterface;
use Response\Payloads\Failure;
use Response\Payloads\Generic;
use Response\Payloads\Redirect;

class Response implements ResponseInterface
{
    protected RequestInterface $request;

    protected int $responseCode;
    private string $response;

    //

    private ResponseStage $responseStage;

    //

    public function init(string $response, int $responseCode, RequestInterface $request): self
    {
        $this->responseCode = $responseCode;
        $this->response = $response;

        $this->request = $request;

        //

        $this->responseStage = $this->responseStage();

        return $this;
    }

    public function getRaw(): string
    {
        return $this->response;
    }

    public function getJson()
    {
        return json_decode($this->getRaw());
    }

    public function getResponse(?PayloadInterface $responseClass = null): PayloadInterface
    {
        /** @var PayloadInterface */
        $mapToClass = null;

        $responseStage = $this->responseStage();

        if ($responseStage == ResponseStage::Error) {
            return $this->asFailure();
        }

        if ($responseStage == ResponseStage::Redirect) {
            return $this->asRedirect();
        }

        //

        if ($responseClass != null) {
            return $responseClass->fromJson($this->getJson());
        }

        if ($this->request->getResponseClass() != null) {
            $mapToClass = $this->request->getResponseClass();
        }

        if ($mapToClass != null) {
            return $mapToClass->fromJson($this->getJson());
        }

        return (new Generic)->fromJson($this->getJson());
    }

    public function asFailure(): Failure
    {
        return (new Failure())->fromJson($this->getJson());
    }

    public function asRedirect(): Redirect
    {
        return (new Redirect())->fromJson($this->getJson());
    }

    //

    protected function responseStage(): ResponseStage
    {
        $response_decoded = $this->getJson();

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

    public function getResponseStage(): ResponseStage
    {
        return $this->responseStage;
    }
}
