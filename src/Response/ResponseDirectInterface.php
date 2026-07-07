<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response;

use Paytabs\Sdk\Request\RequestInterface;
use Paytabs\Sdk\Response\Payload\PayloadInterface;
use Paytabs\Sdk\Response\Payload\Payloads\Failure;
use Paytabs\Sdk\Response\Payload\Payloads\Redirect;

interface ResponseDirectInterface extends ResponseInterface
{
    public function setResponseCode(int $responseCode): static;

    public function setRequest(RequestInterface $request);

    public function isProcessed(): bool;

    public function isFailure(): bool;

    public function getFailure(): Failure;

    public function isRedirect(): bool;

    public function getRedirect(): Redirect;

    public function getPayloadMapped(): PayloadInterface;
}
