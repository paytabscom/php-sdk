<?php

namespace Holder\Parts;

use Holder\PartInterface;

class PaymentMethods implements PartInterface
{
    private ?array $paymentMethods;

    public function __construct(
        ?array $paymentMethods = null
    ) {
        $this->paymentMethods = $paymentMethods;
    }

    public function includeMethod(string $code): self
    {
        $this->paymentMethods ??= [];
        $this->paymentMethods[] = $code;

        return $this;
    }

    public function excludeMethod(string $code): self
    {
        $this->paymentMethods ??= [];
        $this->paymentMethods[] = "-{$code}";

        return $this;
    }

    public function build(): array
    {
        $paymentMethods = array_unique($this->paymentMethods);

        return [
            'payment_methods' => $paymentMethods,
        ];
    }
}
