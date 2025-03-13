<?php

namespace Paytabs\Sdk\Request\Payload\Payloads;

use Paytabs\Sdk\Request\Payload\Parts\TransactionRef;
use Paytabs\Sdk\Response\PayloadInterface;
use Paytabs\Sdk\Response\Payloads\Payment\Completed;

class Followup extends PaymentRequest
{
    /** @return Completed */
    public function getResponseClass(): ?PayloadInterface
    {
        return new Completed();
    }

    public function buildTransactionRef(string $tranRef)
    {
        $this->product->buildBody(
            new TransactionRef($tranRef)
        );

        return $this;
    }
}
