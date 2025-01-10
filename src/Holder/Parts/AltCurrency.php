<?php

namespace Holder\Parts;

use Holder\PartInterface;

class AltCurrency implements PartInterface
{
    public string $altCurrency;

    public function __construct(
        string $altCurrency
    ) {
        $this->altCurrency = $altCurrency;
    }

    public function build(): array
    {
        return [
            'alt_currency' => $this->altCurrency,
        ];
    }
}
