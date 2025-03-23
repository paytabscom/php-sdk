<?php

namespace Paytabs\Sdk\Request\Payload\Payloads;

use Paytabs\Sdk\Request\Payload\Paytabs\PaytabsBuilder;
use Paytabs\Sdk\Request\Payload\Parts\InvoiceMarkPaid as InvoiceMarkPaidPart;

class InvoiceMarkPaid extends PaytabsBuilder
{
    public function buildInvoiceMarkPaid(string $profileId, string $invoiceId, string $invoiceCurrency, float $invoiceTotal, string $payMethod, string $payDescription)
    {
        $this->product->buildBody(
            new InvoiceMarkPaidPart(
                $profileId,
                $invoiceId,
                $invoiceCurrency,
                $invoiceTotal,
                $payMethod,
                $payDescription
            )
        );

        return $this;
    }
}
