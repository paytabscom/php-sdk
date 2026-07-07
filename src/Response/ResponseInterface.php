<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response;

use Paytabs\Sdk\Response\Payload\PayloadInterface;

interface ResponseInterface
{
    public function setResponse(mixed $raw_response): static;

    public function setPayload(PayloadInterface $payloadClass): static;

    public function getPayload(): ?PayloadInterface;
}
