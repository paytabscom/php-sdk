<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Payload\Payloads\Invoice;

use Paytabs\Sdk\Request\Payload\Parts\InvoiceMarkPaid as InvoiceMarkPaidPart;
use Paytabs\Sdk\Request\Payload\Paytabs\PaytabsBuilder;

class InvoiceMarkPaid extends PaytabsBuilder
{
    public function buildInvoiceMarkPaid(InvoiceMarkPaidPart $invoiceMarkPaidPart)
    {
        $this->product->buildBody(
            $invoiceMarkPaidPart
        );

        return $this;
    }
}
