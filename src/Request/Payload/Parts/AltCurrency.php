<?php

namespace Paytabs\Sdk\Request\Payload\Parts;

class AltCurrency extends AbstractPart
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
