<?php

namespace Paytabs\Sdk\Holder;

interface PartInterface
{
    /** @return array<string, array|string> */
    public function build(): array;
}
