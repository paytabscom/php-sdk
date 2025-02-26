<?php

namespace Paytabs\Sdk\Holder\Builders;

use Paytabs\Sdk\Holder\Parts\CardDetails;
use Paytabs\Sdk\Response\PayloadInterface;
use Paytabs\Sdk\Response\Payloads\Payment\Completed;

class OwnForm extends PrimaryPayment
{
    /** @return Completed */
    public function getResponseClass(): ?PayloadInterface
    {
        return new Completed();
    }

    public function buildCardDetails(
        string $pan,
        int $expiryYear,
        int $expiryMonth,
        ?string $cvv = null
    ) {
        $this->product->buildBody(
            new CardDetails($pan, $expiryYear, $expiryMonth, $cvv)
        );

        return $this;
    }
}
