<?php

namespace Holder\Builders\Invoice;

use Holder\Builders\PaymentRequest;
use Holder\Parts\Invoice\Invoice as InvoicePart;

class Invoice extends PaymentRequest
{
    //

    public function setInvoice(InvoicePart $invoice)
    {
        $this->product->buildBody($invoice);

        return $this;
    }
}
