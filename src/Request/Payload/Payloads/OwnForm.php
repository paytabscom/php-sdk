<?php

namespace Paytabs\Sdk\Request\Payload\Payloads;

use Paytabs\Sdk\PaymentMethod\AbstractMethod;
use Paytabs\Sdk\Request\Payload\Parts\CardDetails;
use Paytabs\Sdk\Request\Payload\Parts\PaymentMethods;
use Paytabs\Sdk\Response\Payload\PayloadInterface;
use Paytabs\Sdk\Response\Payload\Payloads\Payment\Completed;

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

    public function buildPaymentMethods(array|PaymentMethods $methods): void
    {
        throw new \Exception('Payment Methods not allowed in Own Form');
    }

    public function buildPaymentMethod(AbstractMethod|string $method): void
    {
        throw new \Exception('Payment Methods not allowed in Own Form');
    }
}
