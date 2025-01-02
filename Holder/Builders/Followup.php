<?php

namespace Holder\Builders;

use Holder\Parts\TransactionRef;
use Response\Payload\Payment\Completed;

class Followup extends PaymentRequest
{
    protected string $responseClass = Completed::class;

    public function setTransactionRef(string $tranRef)
    {
        $this->product->buildBody(
            new TransactionRef($tranRef)
        );

        return $this;
    }
}
