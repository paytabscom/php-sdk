<?php

namespace Paytabs\Sdk\Request\Payload\Payloads;

use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Payload\BuilderInterface;
use Paytabs\Sdk\Request\Payload\PartInterface;
use Paytabs\Sdk\Request\Payload\PayloadInterface;
use Paytabs\Sdk\Request\Payload\Paytabs\PaytabsPayload;
use Paytabs\Sdk\Response\Payload\PayloadInterface as ResponsePayloadInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractHolder implements BuilderInterface
{
    protected PayloadInterface $product;

    protected ?ResponsePayloadInterface $responseClass = null;

    protected LoggerInterface $logger;

    public function __construct()
    {
        $this->product = new PaytabsPayload();

        $this->logger = Paytabs::getLogger();
    }

    public function build(PartInterface $part)
    {
        $this->product->buildBody($part);

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
