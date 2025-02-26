<?php

namespace Paytabs\Sdk\Holder\Parts;

class CardDetails extends AbstractPart
{
    private string $pan;
    private int $expiryYear;
    private int $expiryMonth;
    private string $cvv;

    public function __construct(
        string $pan,
        int $expiryYear,
        int $expiryMonth,
        ?string $cvv
    ) {
        $this->pan = $pan;
        $this->expiryYear = $expiryYear;
        $this->expiryMonth = $expiryMonth;
        $this->cvv = $cvv;
    }

    public function build(): array
    {
        return [
            'card_details' => [
                'pan' => $this->pan,
                'cvv' => $this->cvv,
                'expiry_year' => $this->expiryYear,
                'expiry_month' => $this->expiryMonth,
            ]
        ];
    }
}
