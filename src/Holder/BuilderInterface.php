<?php

namespace Paytabs\Sdk\Holder;

use Response\PayloadInterface as ResponsePayload;

interface BuilderInterface
{
    public function getPayload(): PayloadInterface;

    public function getResponseClass(): ?ResponsePayload;
}
