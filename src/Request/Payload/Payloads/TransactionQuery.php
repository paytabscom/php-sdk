<?php

namespace Paytabs\Sdk\Request\Payload\Payloads;

use Paytabs\Sdk\Request\Payload\Parts\GenericPart;
use Paytabs\Sdk\Request\Payload\Parts\TransactionRef;
use Paytabs\Sdk\Response\Payload\PayloadInterface;
use Paytabs\Sdk\Response\Payload\Payloads\Payment\Completed;

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
