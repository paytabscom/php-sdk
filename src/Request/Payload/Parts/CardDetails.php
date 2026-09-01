<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Payload\Parts;

class CardDetails extends AbstractPart
{
    // 13-19 digits: ISO/IEC 7812 allows up to 19, and the SDK ships UnionPay
    // and Maestro methods whose PANs exceed 16.
    public const PAN_REGEX = '/^\d{13,19}$/';

    // Digits between 3 and 4 digits
    public const CVV_REGEX = '/^\d{3,4}$/';

    private string $pan;
    private int $expiryYear;
    private int $expiryMonth;
    private ?string $cvv;

    public function __construct(
        string $pan,
        int $expiryYear,
        int $expiryMonth,
        ?string $cvv
    ) {
        // Remove amy empty space or '-' from the PAN
        $this->pan = str_replace([' ', '-'], '', $pan);

        $this->expiryYear = $expiryYear;
        $this->expiryMonth = $expiryMonth;
        $this->cvv = $cvv;

        if ($expiryMonth < 1 || $expiryMonth > 12) {
            throw new \InvalidArgumentException('Invalid expiry Month');
        }

        $current_year = (int) date('Y');
        $current_month = (int) date('n');

        if ($expiryYear < $current_year || $expiryYear > $current_year + 10) {
            throw new \InvalidArgumentException('Invalid expiry Year');
        }

        // Compare Month of the same year
        if ($expiryYear === $current_year && $expiryMonth < $current_month) {
            throw new \InvalidArgumentException('Card has expired');
        }

        if (!static::isValidPAN($this->pan)) {
            throw new \InvalidArgumentException('Invalid Card number format');
        }

        if (!static::passesLuhn($this->pan)) {
            throw new \InvalidArgumentException('Invalid Card number (checksum)');
        }

        if (null !== $cvv && !static::isValidCVV($cvv)) {
            throw new \InvalidArgumentException('Invalid CVV format');
        }
    }

    public static function isValidPAN(string $pan): bool
    {
        return 1 === preg_match(static::PAN_REGEX, $pan);
    }

    public static function isValidCVV(string $cvv): bool
    {
        return 1 === preg_match(static::CVV_REGEX, $cvv);
    }

    /**
     * Luhn (mod 10) check digit, per ISO/IEC 7812.
     *
     * Catches transposed and mistyped digits before a request is spent on them.
     */
    public static function passesLuhn(string $pan): bool
    {
        $sum = 0;
        $double = false;

        for ($i = \strlen($pan) - 1; $i >= 0; --$i) {
            $digit = (int) $pan[$i];

            if ($double) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            $sum += $digit;
            $double = !$double;
        }

        return 0 === $sum % 10;
    }

    public function build(): array
    {
        return [
            'card_details' => [
                'pan' => $this->pan,
                'cvv' => $this->cvv,
                'expiry_year' => $this->expiryYear,
                'expiry_month' => $this->expiryMonth,
            ],
        ];
    }
}
