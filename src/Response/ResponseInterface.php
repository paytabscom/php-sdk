<?php

namespace Paytabs\Sdk\Response;

interface ResponseInterface
{
    public function setResponse(mixed $raw_response): static;

    public function setPayload(PayloadInterface $payloadClass): static;

    public function getPayload(): ?PayloadInterface;
}
