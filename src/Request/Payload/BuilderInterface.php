<?php

namespace Paytabs\Sdk\Request\Payload;

use Paytabs\Sdk\Response\Payload\PayloadInterface as ResponsePayload;

interface BuilderInterface
{
    public function getPayload(): PayloadInterface;

    public function getResponseClass(): ?ResponsePayload;
}
