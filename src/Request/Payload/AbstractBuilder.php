<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Payload;

use Paytabs\Sdk\Response\Payload\PayloadInterface as ResponsePayloadInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractBuilder implements BuilderInterface
{
    protected PayloadInterface $product;

    protected ?ResponsePayloadInterface $responseClass = null;

    protected LoggerInterface $logger;

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    public function getPayload(): PayloadInterface
    {
        return $this->product;
    }

    public function getResponseClass(): ?ResponsePayloadInterface
    {
        return $this->responseClass;
    }
}
