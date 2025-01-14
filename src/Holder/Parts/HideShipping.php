<?php

namespace Paytabs\Sdk\Holder\Parts;

class HideShipping extends AbstractPart
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
