<?php

namespace Response;

use Enums\ResponseStage;
use Request\RequestInterface;
use Response\Payloads\Failure;
use Response\Payloads\Redirect;

interface ResponseInterface
{
    public function init(string $response, int $responseCode, RequestInterface $request): self;

    public function getResponseStage(): ResponseStage;

    public function getRaw(): string;
    public function getJson();

    public function getResponse(?PayloadInterface $responseClass = null): PayloadInterface;

    public function asFailure(): Failure;
    public function asRedirect(): Redirect;
}
