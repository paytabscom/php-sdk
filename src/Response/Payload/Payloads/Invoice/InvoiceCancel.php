<?php

namespace Paytabs\Sdk\Response\Payload\Payloads\Invoice;

use Paytabs\Sdk\Enums\TranStatus;
use Paytabs\Sdk\Response\Payload\Payloads\Paytabs;

class InvoiceCancel extends Paytabs
{

    public ?string $code; // "TST2104500076067"

    public string $message; // "A"

}
