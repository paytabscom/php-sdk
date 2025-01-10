<?php

namespace Paytabs\Sdk\Response;

interface PayloadInterface
{
    public function init(): self;

    public function fromJson($jsonResponse): self;
}
