<?php

namespace Paytabs\Sdk\Holder\Builders\Invoice;

use Paytabs\Sdk\Holder\Builders\AbstractHolder;
use Paytabs\Sdk\Holder\Parts\GenericPart;

class InvoiceStatus extends AbstractHolder
{
    public function buildInvoiceId(string $invoiceId)
    {
        $this->product->buildBody(new GenericPart([
            'invoice_id' => $invoiceId,
        ]));

        return $this;
    }
}
