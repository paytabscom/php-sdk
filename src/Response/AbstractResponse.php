<?php

namespace Paytabs\Sdk\Response;

abstract class AbstractResponse implements ResponseInterface
{
    protected ?PayloadInterface $payload = null;

    public function setResponse(mixed $raw_response): static
    {
        $this->payload->setResponseData($raw_response);

        return $this;
    }

    public function setPayload(PayloadInterface $payloadClass): static
    {
        $this->payload = $payloadClass;

        return $this;
    }

    public function getPayload(): ?PayloadInterface
    {
        return $this->payload;
    }
}
