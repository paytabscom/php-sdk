<?php

namespace Holder\Builders;

use Enums\TokenPaymentFrequency;
use Enums\TokenType;
use Holder\Parts\CustomerDetails;
use Holder\Parts\HideShipping;
use Holder\Parts\PaypageLang;
use Holder\Parts\ShippingDetails;
use Holder\Parts\Tokenise;
use Holder\Parts\TokeniseEnhanced;

abstract class PrimaryPayment extends AirlineData
{
    public function setCustomerDetails(CustomerDetails $customerDetails)
    {
        $this->product->buildBody($customerDetails);

        return $this;
    }

    public function setShippingDetails(ShippingDetails $shippingDetails)
    {
        $this->product->buildBody($shippingDetails);

        return $this;
    }

    public function setPaypageLang(string $lang)
    {
        $this->product->buildBody(
            new PaypageLang($lang)
        );

        return $this;
    }

    public function setTokenise(bool $on = false, int $tokenFormat = 2, bool $isOptional = false)
    {
        if ($on) {
            $this->product->buildBody(
                new Tokenise($tokenFormat, $isOptional)
            );
        }

        return $this;
    }


    /**
     * @param int $tokenFormat integer between 2 and 6, Set the Token format
     * @param TokenType $tokenType
     * @param int $counter
     * @param int $totalCount
     * @param bool $isOptional Display the save card option on the payment page
     */
    public function setTokeniseEnhanced(
        TokenType $tokenType = TokenType::Registered,
        int $tokenFormat = 2,

        ?TokenPaymentFrequency $paymentFrequency = null,
        ?float $minAmountPerPayment = null,
        ?float $maxAmountPerPayment = null,
        ?int $minDaysBetweenPayments = null,
        ?string $startDate = null,
        ?string $expiryDate = null,

        ?int $counter = null,
        ?int $totalCount = null,

        bool $isOptional = false
    ) {
        $obj = (new TokeniseEnhanced(
            $tokenType,
            $tokenFormat,
            $isOptional
        ))->setPaymentInfo(
            $paymentFrequency,
            $minAmountPerPayment,
            $maxAmountPerPayment,
            $minDaysBetweenPayments,
            $startDate,
            $expiryDate,
        )->setCounter(
            $counter,
            $totalCount,
        );

        return $this->setTokeniseEnhancedObj($obj);
    }

    public function setTokeniseEnhancedObj(
        TokeniseEnhanced $tokeniseEnhanced
    ) {
        $this->product->buildBody(
            $tokeniseEnhanced
        );

        return $this;
    }
}
