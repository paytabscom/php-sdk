<?php

namespace Paytabs\Sdk\Response;

use Paytabs\Sdk\Enums\ResponseStage;
use Paytabs\Sdk\Request\RequestInterface;
use Paytabs\Sdk\Response\Payloads\Failure;
use Paytabs\Sdk\Response\Payloads\Redirect;

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
