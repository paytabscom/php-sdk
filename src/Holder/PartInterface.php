<?php

namespace Paytabs\Sdk\Holder;

interface PartInterface
{
    public static function init(): static;

    /** @return array<string, string|array> */
    public function build(): array;
}
