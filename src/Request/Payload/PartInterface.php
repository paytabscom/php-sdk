<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Payload;

interface PartInterface
{
    /** @return array<string, array|string> */
    public function build(): array;
}
