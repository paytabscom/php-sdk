<?php

namespace Paytabs\Sdk\Request\Payload\Parts;

class CardDetails extends AbstractPart
{
    // Digits between 13 and 16 digits
    public const PAN_REGEX = '/^\d{13,16}$/';

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
        if ($expiryYear < $current_year || $expiryYear > $current_year + 10) {
            throw new \InvalidArgumentException('Invalid expiry Year');
        }

        if (!static::isValidPAN($this->pan)) {
            throw new \InvalidArgumentException('Invalid Card number format');
        }

        if ($cvv && !static::isValidCVV($this->cvv)) {
            throw new \InvalidArgumentException('Invalid CVV format');
        }
    }

    public static function isValidPAN(string $pan)
    {
        return preg_match(static::PAN_REGEX, $pan);
    }

    public static function isValidCVV(string $cvv)
    {
        return preg_match(static::CVV_REGEX, $cvv);
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
