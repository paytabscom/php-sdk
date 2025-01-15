<?php

namespace Paytabs\Sdk\Holder\Builders\Invoice;

use Paytabs\Sdk\Holder\Builders\AbstractHolder;

class InvoiceStatusGet extends AbstractHolder
{
    public function buildInvoiceId(string $invoiceId)
    {
        $this->product->buildPath(
            [
                '{invoice_id}' => $invoiceId,
            ]
        );

        return $this;
    }
}
