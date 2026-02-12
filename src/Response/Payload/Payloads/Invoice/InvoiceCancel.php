<?php

namespace Paytabs\Sdk\Response\Payload\Payloads\Invoice;

use Paytabs\Sdk\Response\Payload\Payloads\Paytabs;

class InvoiceCancel extends Paytabs
{
    public ?string $code;

    public string $message;
}
