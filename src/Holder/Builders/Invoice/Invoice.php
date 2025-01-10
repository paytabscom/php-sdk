<?php

namespace Paytabs\Sdk\Holder\Builders\Invoice;

use Holder\Builders\PrimaryPayment;
use Holder\Parts\Invoice\Invoice as InvoicePart;

class Invoice extends PrimaryPayment
{
    //

    public function buildInvoice(InvoicePart $invoice)
    {
        $this->product->buildBody($invoice);

        return $this;
    }
}
