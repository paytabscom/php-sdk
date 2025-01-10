<?php

namespace Holder\Parts;

use Helpers\NextIf;
use Holder\PartInterface;

class PaymentMethods implements PartInterface, NextIf
{
    private ?array $paymentMethods;

    protected function __construct(
        ?array $paymentMethods = null
    ) {
        $this->paymentMethods = $paymentMethods;
    }

    public static function init(?array $methods = null): self
    {
        return new PaymentMethods($methods);
    }

    public function includeMethod(string $code): self
    {
        $this->add([$code]);

        return $this;
    }

    public function includeMethods(array $codes): self
    {
        $this->add($codes);

        return $this;
    }

    public function excludeMethod(string $code): self
    {
        $this->add([$code], true);

        return $this;
    }

    public function excludeMethods(array $codes): self
    {
        $this->add($codes, true);

        return $this;
    }

    private function add(array $codes, bool $isExclude = false): void
    {
        if ($this->readNextIf() === false) {
            return;
        }

        $this->paymentMethods ??= [];

        $codesArray = $codes;

        if ($isExclude) {
            $codesArray = array_map(fn($code): string => "-{$code}", $codesArray);
        }

        $this->paymentMethods = array_merge($this->paymentMethods, $codesArray);
    }

    //

    private ?bool $nextIf = null;
    public function nextIf(bool $cond): self
    {
        $this->nextIf = $cond;
        return $this;
    }

    public function nextSkipIf(bool $cond): self
    {
        return $this->nextIf(!$cond);
    }

    public function readNextIf(): ?bool
    {
        $next = $this->nextIf;

        $this->nextIf = null;

        return $next;
    }

    //

    public function build(): array
    {
        $paymentMethods = array_unique($this->paymentMethods);

        return [
            'payment_methods' => $paymentMethods,
        ];
    }
}
