<?php

namespace Holder\Builders;

use Holder\BuilderInterface;
use Holder\PartInterface;
use Holder\Payload\PaytabsPayload;
use Holder\PayloadInterface;

abstract class AbstractHolder implements BuilderInterface
{
    protected PayloadInterface $product;

    protected array $expectedResponses;

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

    public function expectedResponses(): array
    {
        return $this->expectedResponses;
    }
}
