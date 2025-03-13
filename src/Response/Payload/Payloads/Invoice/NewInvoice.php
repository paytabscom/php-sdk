<?php

namespace Paytabs\Sdk\Response\Payload\Payloads\Invoice;

use Paytabs\Sdk\Response\Payload\Payloads\Paytabs;

class NewInvoice extends Paytabs
{
    public string $invoice_id;
    public string $invoice_link;

    public int $trace_code;
}
