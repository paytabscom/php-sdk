<?php

namespace Paytabs\Sdk\Holder\Payloads\Invoice;

use Paytabs\Sdk\Holder\Payloads\AbstractHolder;
use Paytabs\Sdk\Holder\Parts\GenericPart;

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
