<?php

namespace Holder;

interface PartInterface
{
    /** @return array<string, string|array> */
    public function build(): array;
}
