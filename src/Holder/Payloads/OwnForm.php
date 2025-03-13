<?php

namespace Paytabs\Sdk\Holder\Payloads;

use Paytabs\Sdk\Holder\Parts\CardDetails;
use Paytabs\Sdk\Holder\Parts\PaymentMethods;
use Paytabs\Sdk\Response\PayloadInterface;
use Paytabs\Sdk\Response\Payloads\Payment\Completed;

class OwnForm extends PrimaryPayment
{
    /** @return Completed */
    public function getResponseClass(): ?PayloadInterface
    {
        return new Completed();
    }

    public function buildCardDetails(
        string $pan,
        int $expiryYear,
        int $expiryMonth,
        ?string $cvv = null
    ) {
        $this->product->buildBody(
            new CardDetails($pan, $expiryYear, $expiryMonth, $cvv)
        );

        return $this;
    }

    public function buildPaymentMethods(array|PaymentMethods $methods)
    {
        throw new \Exception('Payment Methods not allowed in Own Form');
    }

    public function buildPaymentMethod(string $method)
    {
        throw new \Exception('Payment Methods not allowed in Own Form');
    }
}
