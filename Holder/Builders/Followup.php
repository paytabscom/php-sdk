<?php

namespace Holder\Builders;

use Holder\Parts\TransactionRef;

class Followup extends PaymentRequest
{
    public function setTransactionRef(string $tranRef)
    {
        $this->product->buildBody(
            new TransactionRef($tranRef)
        );

        return $this;
    }
}
