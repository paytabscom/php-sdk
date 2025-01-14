<?php

namespace Paytabs\Sdk\Holder\Builders;

use Paytabs\Sdk\Holder\Parts\TransactionRef;
use Paytabs\Sdk\Response\Payloads\Payment\Completed;
use Paytabs\Sdk\Response\PayloadInterface;

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
