<?php

namespace Paytabs\Sdk\Request\Payload\Payloads\Invoice;

use Paytabs\Sdk\Request\Payload\Parts\InvoiceMarkPaid as InvoiceMarkPaidPart;
use Paytabs\Sdk\Request\Payload\Paytabs\PaytabsBuilder;

class InvoiceMarkPaid extends PaytabsBuilder
{
    public function buildInvoiceMarkPaid(string $invoiceId, string $invoiceCurrency, float $invoiceTotal, string $payMethod, string $payDescription)
    {
        $this->product->buildBody(
            new InvoiceMarkPaidPart(
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
