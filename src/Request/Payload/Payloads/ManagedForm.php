<?php

namespace Paytabs\Sdk\Request\Payload\Payloads;

use Paytabs\Sdk\Request\Payload\Parts\ManagedFormToken;

class ManagedForm extends PrimaryPayment
{
    public function buildPaymentToken(string $paymentToken)
    {
        $this->product->buildBody(
            new ManagedFormToken($paymentToken)
        );

        return $this;
    }
}
