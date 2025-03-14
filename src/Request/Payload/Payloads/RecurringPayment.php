<?php

namespace Paytabs\Sdk\Request\Payload\Payloads;

use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Request\Payload\Parts\ShippingDetails;
use Paytabs\Sdk\Request\Payload\Parts\Token;
use Paytabs\Sdk\Request\Payload\Parts\TokenEnhanced;

class RecurringPayment extends HostedPage
{
    public function buildToken(Token $token)
    {
        $this->product->buildBody($token);

        return $this;
    }

    public function buildTokenEnhanced(TokenEnhanced $token)
    {
        $this->product->buildBody($token);

        return $this;
    }

    public function buildTransaction(TranType $tran_type, TranClass $tran_class = TranClass::Ecom)
    {
        if (!$tran_type->supportRecurring()) {
            throw new \Exception('Invalid transaction type for Recurring Payment');
        }

        return parent::buildTransaction($tran_type, $tran_class);
    }

    public function buildShippingDetails(ShippingDetails $shippingDetails)
    {
        throw new \Exception('Shipping details are not allowed in Recurring Payment');
    }

    public function buildCardFilter(string $cardFilter, string $cardFilterTitle)
    {
        throw new \Exception('Card filter is not allowed in Recurring Payment');
    }
}
