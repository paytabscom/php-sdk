<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Payload\Parts;

use Paytabs\Sdk\PaymentMethod\AbstractMethod;

class PaymentMethods extends AbstractPart
{
    /** @var string[] */
    private ?array $paymentMethods;

    protected function __construct(
        ?array $paymentMethods = null
    ) {
        if ($paymentMethods) {
            $this->add($paymentMethods);
        }
    }

    public static function init(?array $methods = null): self
    {
        return new self($methods);
    }

    public function includeMethod(AbstractMethod|string $code): self
    {
        $this->add([$code]);

        return $this;
    }

    /**
     * @param AbstractMethod[]|string[] $codes
     */
    public function includeMethods(array $codes): self
    {
        $this->add($codes);

        return $this;
    }

    public function excludeMethod(AbstractMethod|string $code): self
    {
        $this->add([$code], true);

        return $this;
    }

    /**
     * @param AbstractMethod[]|string[] $codes
     */
    public function excludeMethods(array $codes): self
    {
        $this->add($codes, true);

        return $this;
    }

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

    /**
     * @param AbstractMethod[]|string[] $codes
     */
    private function add(array $codes, bool $isExclude = false): void
    {
        if (false === $this->readNextIf()) {
            return;
        }

        $this->paymentMethods ??= [];

        // Convert AbstractMethod to code string
        $codesArray = $this->convertToCodes($codes);

        if ($isExclude) {
            $codesArray = array_map(static fn ($code): string => "-{$code}", $codesArray);
        }

        $this->paymentMethods = array_merge($this->paymentMethods, $codesArray);
    }

    /**
     * @param AbstractMethod[]|string[] $methods
     */
    private function convertToCodes(array $methods): array
    {
        // Convert AbstractMethod to code string
        $codesArray = array_map(
            static fn ($code): string => ($code instanceof AbstractMethod) ? $code::CODE : $code,
            $methods
        );

        return $codesArray;
    }
}
