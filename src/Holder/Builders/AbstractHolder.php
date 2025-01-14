<?php

namespace Paytabs\Sdk\Holder\Builders;

use Paytabs\Sdk\Holder\BuilderInterface;
use Paytabs\Sdk\Holder\PartInterface;
use Paytabs\Sdk\Holder\Payload\PaytabsPayload;
use Paytabs\Sdk\Holder\PayloadInterface;
use Paytabs\Sdk\Response\PayloadInterface as ResponsePayloadInterface;

abstract class AbstractHolder implements BuilderInterface
{
    protected PayloadInterface $product;

    protected ?ResponsePayloadInterface $responseClass = null;

    //

    public function __construct()
    {
        $this->product = new PaytabsPayload();
    }

    //

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
