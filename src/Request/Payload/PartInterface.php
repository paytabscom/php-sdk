<?php

namespace Paytabs\Sdk\Request\Payload;

interface PartInterface
{
    public static function init(): static;

    /** @return array<string, string|array> */
    public function build(): array;
}
