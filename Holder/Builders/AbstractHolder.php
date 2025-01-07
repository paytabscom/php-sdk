<?php

namespace Holder\Builders;

use Holder\BuilderInterface;
use Holder\PartInterface;
use Holder\Payload\PaytabsPayload;
use Holder\PayloadInterface;
use Response\PayloadInterface as ResponsePayloadInterface;

abstract class AbstractHolder implements BuilderInterface
{
    protected PayloadInterface $product;

    protected ResponsePayloadInterface $responseClass;

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
        if (!isset($this->responseClass)) {
            return null;
        }

        return $this->responseClass->init();
    }
}
