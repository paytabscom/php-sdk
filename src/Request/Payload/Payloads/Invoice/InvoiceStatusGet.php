<?php

namespace Paytabs\Sdk\Request\Payload\Payloads\Invoice;

use Paytabs\Sdk\Request\Payload\Payloads\AbstractHolder;
use Paytabs\Sdk\Request\Payload\Parts\GenericPart;

class InvoiceStatusGet extends AbstractHolder
{
    public function buildInvoiceId(string $invoiceId)
    {
        $this->product->buildPath(new GenericPart(
            [
                '{invoice_id}' => $invoiceId,
            ]
        ));

        return $this;
    }
}
