<?php

namespace Paytabs\Sdk\Response;

abstract class AbstractResponse implements ResponseInterface
{
    protected ?PayloadInterface $payload = null;

    //

    public function setResponse(mixed $raw_response)
    {
        $this->payload->setResponseData($raw_response);

        return $this;
    }

    public function setPayload(PayloadInterface $payloadClass)
    {
        $this->payload = $payloadClass;

        return $this;
    }

    public function getPayload(): ?PayloadInterface
    {
        return $this->payload;
    }
}
