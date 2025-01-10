<?php

namespace Holder\Builders;

use Holder\Parts\TransactionRef;
use Response\Payloads\Payment\Completed;
use Response\PayloadInterface;

class Followup extends PaymentRequest
{
    /** @return Completed */
    public function getResponseClass(): ?PayloadInterface
    {
        return new Completed();
    }

    //

    public function buildTransactionRef(string $tranRef)
    {
        $this->product->buildBody(
            new TransactionRef($tranRef)
        );

        return $this;
    }
}
