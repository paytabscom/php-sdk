<?php

namespace Holder\Builders;

use Holder\BuilderInterface;
use Holder\PartInterface;
use Holder\Payload\PaytabsPayload;
use Holder\PayloadInterface;

abstract class AbstractHolder implements BuilderInterface
{
    protected PayloadInterface $product;

    //

    public function __construct()
    {
        $this->product = new PaytabsPayload;
    }

    //

    public function set(PartInterface $part)
    {
        $this->product->buildBody($part);

        return $this;
    }

    public function getPayload(): PayloadInterface
    {
        return $this->product;
    }
}
