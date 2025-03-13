<?php

namespace Paytabs\Sdk\Request\Payload\Payloads\Invoice;

use Paytabs\Sdk\Request\Payload\Parts\GenericPart;
use Paytabs\Sdk\Request\Payload\Payloads\AbstractHolder;

class InvoiceStatus extends AbstractHolder
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
