<?php

namespace Paytabs\Sdk\Holder\Parts;

class Donation extends AbstractPart
{
    private bool $donationMode;
    private int $cartMin;
    private int $cartMax;

    public function __construct(
        string $donationMode,
        string $cartMin,
        string $cartMax
    ) {
        $this->donationMode = $donationMode;
        $this->cartMin = $cartMin;
        $this->cartMax = $cartMax;
    }

    public function build(): array
    {
        return [
            'donation_mode' => $this->donationMode,
            'cart_min' => $this->cartMin,
            'cart_max' => $this->cartMax,
        ];
    }
}
