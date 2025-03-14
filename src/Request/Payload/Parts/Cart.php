<?php

namespace Paytabs\Sdk\Request\Payload\Parts;

class Cart extends AbstractPart
{
    private string $cartId;
    private string $currency;
    private float $amount;
    private string $cartDescription;

    public function __construct(
        string $cartId,
        string $currency,
        float $amount,
        string $cartDescription
    ) {
        $this->cartId = $cartId;
        $this->currency = $currency;
        $this->amount = $amount;
        $this->cartDescription = $cartDescription;
    }

    public function build(): array
    {
        return [
            'cart_id' => $this->cartId,
            'cart_currency' => $this->currency,
            'cart_amount' => $this->amount,
            'cart_description' => $this->cartDescription,
        ];
    }
}
