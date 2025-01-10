<?php

namespace Paytabs\Sdk\Response\Payloads\Invoice;

use Response\Payloads\Paytabs;

class NewInvoice extends Paytabs
{
    public string $invoice_id;
    public string $invoice_link;

    public int $trace_code;
}
