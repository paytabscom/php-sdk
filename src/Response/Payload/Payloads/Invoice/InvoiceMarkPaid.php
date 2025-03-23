<?php

namespace Paytabs\Sdk\Response\Payload\Payloads\Invoice;

use Paytabs\Sdk\Response\Payload\Payloads\Paytabs;

class InvoiceMarkPaid extends Paytabs
{
    public ?string $code;

    public string $message;
}
