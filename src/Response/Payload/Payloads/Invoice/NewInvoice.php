<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Payload\Payloads\Invoice;

use Paytabs\Sdk\Response\Payload\Payloads\Paytabs;

class NewInvoice extends Paytabs
{
    public ?int $invoice_id = null;
    public ?string $invoice_link = null;

    public ?string $trace_code = null;
}
