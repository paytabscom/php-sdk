<?php

namespace Paytabs\Sdk\Holder\Parts;

use Paytabs\Sdk\Holder\PartInterface;

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
