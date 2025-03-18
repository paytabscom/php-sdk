<?php

namespace Paytabs\Sdk\Request\Payload\Payloads\Invoice;

use Paytabs\Sdk\Request\Payload\Parts\GenericPart;
use Paytabs\Sdk\Request\Payload\Paytabs\PaytabsBuilder;

class InvoiceCancel extends PaytabsBuilder
{
    public function buildInvoiceId(string $invoiceId)
    {
        $this->product->buildBody(new GenericPart(
            [
                'invoice_id' => $invoiceId,
            ]
        ));

        return $this;
    }
}
