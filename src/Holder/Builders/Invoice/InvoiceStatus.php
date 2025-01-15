<?php

namespace Paytabs\Sdk\Holder\Builders\Invoice;

use Paytabs\Sdk\Holder\Builders\AbstractHolder;

class InvoiceStatus extends AbstractHolder
{
    public function buildInvoiceId(string $invoiceId)
    {
        $this->product->buildBody(
            [
                'invoice_id' => $invoiceId,
            ]
        );

        return $this;
    }
}
