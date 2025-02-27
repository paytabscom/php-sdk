<?php

namespace Paytabs\Sdk\Holder\Builders\Invoice;

use Paytabs\Sdk\Holder\Builders\PrimaryPayment;
use Paytabs\Sdk\Holder\Parts\Invoice as InvoicePart;

class Invoice extends PrimaryPayment
{
    public function buildInvoice(InvoicePart $invoice)
    {
        $this->product->buildBody($invoice);

        return $this;
    }
}
