<?php

namespace Paytabs\Sdk\Request\Payload\Payloads;

use Paytabs\Sdk\Request\Payload\Parts\GenericPart;
use Paytabs\Sdk\Request\Payload\Parts\TransactionRef;
use Paytabs\Sdk\Request\Payload\Paytabs\PaytabsBuilder;
use Paytabs\Sdk\Response\Payload\PayloadInterface;
use Paytabs\Sdk\Response\Payload\Payloads\Payment\Completed;
use Paytabs\Sdk\Response\Payload\Payloads\Payment\CompletedArray;

class TransactionQuery extends PaytabsBuilder
{
    /** @return Completed|CompletedArray */
    public function getResponseClass(): ?PayloadInterface
    {
        if ($this->getPayload()->exists('tran_ref', null)) {
            return new Completed();
        }

        return new CompletedArray();
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
