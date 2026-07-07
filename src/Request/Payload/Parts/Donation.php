<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Payload\Parts;

class Donation extends AbstractPart
{
    private bool $donationMode;
    private float $cartMin;
    private float $cartMax;

    public function __construct(
        bool $donationMode,
        float $cartMin,
        float $cartMax
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
