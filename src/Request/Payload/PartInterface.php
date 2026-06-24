<?php

namespace Paytabs\Sdk\Request\Payload;

interface PartInterface
{
    /** @return array<string, array|string> */
    public function build(): array;
}
