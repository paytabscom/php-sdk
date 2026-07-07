<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Payload\Parts;

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
