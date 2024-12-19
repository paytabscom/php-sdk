<?php

namespace Holder\Builders;

use Holder\Parts\TransactionRef;

class TransactionQuery extends AbstractHolder
{
    public function setTransactionRef(string $tran_ref)
    {
        $this->product->buildBody(
            new TransactionRef($tran_ref)
        );

        return $this;
    }

    public function setCartId(string $cart_id)
    {
        $this->product->buildBody(
            [
                'cart_id' => $cart_id,
            ]
        );

        return $this;
    }
}
