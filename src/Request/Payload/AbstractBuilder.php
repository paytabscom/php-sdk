<?php

namespace Paytabs\Sdk\Request\Payload;

use Paytabs\Sdk\Response\Payload\PayloadInterface as ResponsePayloadInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractBuilder implements BuilderInterface
{
    protected PayloadInterface $product;

    protected ?ResponsePayloadInterface $responseClass = null;

    protected LoggerInterface $logger;

    public function getPayload(): PayloadInterface
    {
        return $this->product;
    }

    public function getResponseClass(): ?ResponsePayloadInterface
    {
        return $this->responseClass;
    }
}
