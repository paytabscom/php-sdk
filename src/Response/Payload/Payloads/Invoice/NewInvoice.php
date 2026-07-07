<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Payload\Payloads\Invoice;

use Paytabs\Sdk\Response\Payload\Payloads\Paytabs;

class NewInvoice extends Paytabs
{
    public int $invoice_id;
    public string $invoice_link;

    public string $trace_code;
}
