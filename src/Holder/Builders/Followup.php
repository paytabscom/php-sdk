<?php

namespace Paytabs\Sdk\Holder\Builders;

use Paytabs\Sdk\Holder\Parts\TransactionRef;
use Paytabs\Sdk\Response\Payloads\Payment\Completed;
use Paytabs\Sdk\Response\PayloadInterface;

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
