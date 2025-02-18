<?php

namespace Paytabs\Sdk\Response;

use Paytabs\Sdk\Request\RequestInterface;
use Paytabs\Sdk\Response\Payloads\Failure;
use Paytabs\Sdk\Response\Payloads\Redirect;

interface ResponseDirectInterface extends ResponseInterface
{
    public function setResponseCode(int $responseCode);

    public function setRequest(RequestInterface $request);

    public function isSuccessful(): bool;

    public function isFailure(): bool;

    public function getFailure(): Failure;

    public function isRedirect(): bool;

    public function getRedirect(): Redirect;

    public function getPayloadMapped(): PayloadInterface;
}
