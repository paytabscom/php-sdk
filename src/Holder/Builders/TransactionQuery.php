<?php

namespace Paytabs\Sdk\Holder\Builders;

use Holder\Parts\TransactionRef;
use Response\Payloads\Payment\Completed;
use Response\PayloadInterface;

class TransactionQuery extends AbstractHolder
{
    /** @return Completed */
    public function getResponseClass(): ?PayloadInterface
    {
        return new Completed();
    }

    //

    public function buildTransactionRef(string $tran_ref)
    {
        $this->product->buildBody(
            new TransactionRef($tran_ref)
        );

        return $this;
    }

    public function buildCartId(string $cart_id)
    {
        $this->product->buildBody(
            [
                'cart_id' => $cart_id,
            ]
        );

        return $this;
    }
}
