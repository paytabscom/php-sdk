<?php

namespace Paytabs\Sdk\Holder\Payloads;

use Paytabs\Sdk\Holder\Parts\GenericPart;
use Paytabs\Sdk\Holder\Parts\TransactionRef;
use Paytabs\Sdk\Response\PayloadInterface;
use Paytabs\Sdk\Response\Payloads\Payment\Completed;

class TransactionQuery extends AbstractHolder
{
    /** @return Completed */
    public function getResponseClass(): ?PayloadInterface
    {
        return new Completed();
    }

    public function buildTransactionRef(string $tran_ref)
    {
        $this->product->buildBody(
            new TransactionRef($tran_ref)
        );

        return $this;
    }

    public function buildCartId(string $cart_id)
    {
        $this->product->buildBody(new GenericPart(
            [
                'cart_id' => $cart_id,
            ]
        ));

        return $this;
    }
}
