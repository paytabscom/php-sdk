<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Payload\Parts;

class ManagedFormToken extends AbstractPart
{
    private string $paymentToken;

    public function __construct(
        string $paymentToken,
    ) {
        $this->paymentToken = $paymentToken;
    }

    public function build(): array
    {
        return [
            'payment_token' => $this->paymentToken,
        ];
    }
}
