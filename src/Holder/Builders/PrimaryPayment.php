<?php

namespace Paytabs\Sdk\Holder\Builders;

use Paytabs\Sdk\Enums\TokenPaymentFrequency;
use Paytabs\Sdk\Enums\TokenType;
use Paytabs\Sdk\Holder\Parts\CustomerDetails;
use Paytabs\Sdk\Holder\Parts\CustomerReference;
use Paytabs\Sdk\Holder\Parts\PaymentMethods;
use Paytabs\Sdk\Holder\Parts\PaypageLang;
use Paytabs\Sdk\Holder\Parts\ShippingDetails;
use Paytabs\Sdk\Holder\Parts\Tokenise;
use Paytabs\Sdk\Holder\Parts\TokeniseEnhanced;
use Paytabs\Sdk\Holder\Parts\UserDefined;

abstract class PrimaryPayment extends AirlineData
{
    public function buildCustomerDetails(CustomerDetails $customerDetails)
    {
        $this->product->buildBody($customerDetails);

        return $this;
    }

    public function buildShippingDetails(ShippingDetails $shippingDetails)
    {
        $this->product->buildBody($shippingDetails);

        return $this;
    }

    public function buildPaypageLang(string $lang)
    {
        $this->product->buildBody(
            new PaypageLang($lang)
        );

        return $this;
    }

    public function buildTokenise(bool $on = false, int $tokenFormat = 2, bool $isOptional = false)
    {
        if ($on) {
            $this->product->buildBody(
                new Tokenise($tokenFormat, $isOptional)
            );
        }

        return $this;
    }

    /**
     * @param int  $tokenFormat integer between 2 and 6, Set the Token format
     * @param bool $isOptional  Display the save card option on the payment page
     */
    public function buildTokeniseEnhanced(
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

        return $this->buildTokeniseEnhancedObj($obj);
    }

    public function buildTokeniseEnhancedObj(
        TokeniseEnhanced $tokeniseEnhanced
    ) {
        $this->product->buildBody(
            $tokeniseEnhanced
        );

        return $this;
    }

    public function buildPaymentMethods(array|PaymentMethods $methods)
    {
        if (\is_array($methods)) {
            $methods = new PaymentMethods($methods);
        }

        $this->product->buildBody(
            $methods,
            true
        );

        return $this;
    }

    public function buildPaymentMethod(string $method)
    {
        $this->product->buildBody(
            PaymentMethods::init()->includeMethod($method),
            true
        );

        return $this;
    }

    public function buildCustomerReference(string $customerReference)
    {
        $this->product->buildBody(
            new CustomerReference($customerReference)
        );

        return $this;
    }

    public function buildUserDefined(UserDefined $userDefined)
    {
        $this->product->buildBody($userDefined);

        return $this;
    }
}
