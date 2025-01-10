<?php

namespace Holder\Parts;

use Holder\PartInterface;

class HideShipping implements PartInterface
{
    public bool $hideShipping;

    public function __construct(
        bool $hideShipping = true
    ) {
        $this->hideShipping = $hideShipping;
    }

    public function build(): array
    {
        return [
            'hide_shipping' => $this->hideShipping,
        ];
    }
}
