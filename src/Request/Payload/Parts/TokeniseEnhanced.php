<?php

namespace Paytabs\Sdk\Request\Payload\Parts;

use Paytabs\Sdk\Enums\TokenPaymentFrequency;
use Paytabs\Sdk\Enums\TokenType;

class TokeniseEnhanced extends AbstractPart
{
    private TokenType $tokenType;
    private int $tokenFormat = 2;

    private ?TokenPaymentFrequency $paymentFrequency;
    private ?float $minAmountPerPayment;
    private ?float $maxAmountPerPayment;
    private ?int $minDaysBetweenPayments;
    private ?string $startDate;
    private ?string $expiryDate;

    private ?int $counter = null;
    private ?int $totalCount = null;

    private bool $isOptional = false;

    public function __construct(
        TokenType $tokenType,
        int $tokenFormat = 2,
        bool $isOptional = false
    ) {
        if ($this->readNextIf() === false) {
            return $this;
        }

        $this->tokenType = $tokenType;
        $this->tokenFormat = $tokenFormat;
        $this->isOptional = $isOptional;

        return $this;
    }

    public function setPaymentInfo(
        ?TokenPaymentFrequency $paymentFrequency = null,
        ?float $minAmountPerPayment = null,
        ?float $maxAmountPerPayment = null,
        ?int $minDaysBetweenPayments = null,
        ?string $startDate = null,
        ?string $expiryDate = null
    ): self {
        if ($this->readNextIf() === false) {
            return $this;
        }

        $this->paymentFrequency = $paymentFrequency;
        $this->minAmountPerPayment = $minAmountPerPayment;
        $this->maxAmountPerPayment = $maxAmountPerPayment;
        $this->minDaysBetweenPayments = $minDaysBetweenPayments;
        $this->startDate = $startDate;
        $this->expiryDate = $expiryDate;

        return $this;
    }

    public function setCounter(
        ?int $counter = null,
        ?int $totalCount = null
    ): self {
        if ($this->readNextIf() === false) {
            return $this;
        }

        $this->counter = $counter;
        $this->totalCount = $totalCount;

        return $this;
    }

    public function build(): array
    {
        $_info = [
            'tokenise' => $this->tokenFormat,
            'token_type' => $this->tokenType->value,

            'counter' => $this->counter,
            'total_count' => $this->totalCount,

            'payment_frequency' => $this->paymentFrequency->value,
            'min_amount_per_payment' => $this->minAmountPerPayment,
            'max_amount_per_payment' => $this->maxAmountPerPayment,
            'min_days_between_payments' => $this->minDaysBetweenPayments,
            'start_date' => $this->startDate,
            'expiry_date' => $this->expiryDate,
        ];

        return [
            'token_info' => $_info,
            'show_save_card' => $this->isOptional,
        ];
    }
}
