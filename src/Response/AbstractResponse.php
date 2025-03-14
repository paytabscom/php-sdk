<?php

namespace Paytabs\Sdk\Response;

use Paytabs\Sdk\Response\Payload\PayloadInterface;

abstract class AbstractResponse implements ResponseInterface
{
    protected ?PayloadInterface $payload = null;

    public function setResponse($raw_response)
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
