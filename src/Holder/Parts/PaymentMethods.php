<?php

namespace Paytabs\Sdk\Holder\Parts;

class PaymentMethods extends AbstractPart
{
    /** @var string[] */
    private ?array $paymentMethods;

    protected function __construct(
        ?array $paymentMethods = null
    ) {
        $this->paymentMethods = $paymentMethods;
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
            $codesArray = array_map(static fn ($code): string => "-{$code}", $codesArray);
        }

        $this->paymentMethods = array_merge($this->paymentMethods, $codesArray);
    }

    //

    public function build(): array
    {
        /** array_values used to remove the indexes */
        $paymentMethods = array_values(
            array_unique($this->paymentMethods)
        );

        return [
            'payment_methods' => $paymentMethods,
        ];
    }
}
