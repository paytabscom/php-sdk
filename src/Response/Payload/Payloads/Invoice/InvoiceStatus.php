<?php

namespace Paytabs\Sdk\Response\Payload\Payloads\Invoice;

use Paytabs\Sdk\Enums\TranStatus;
use Paytabs\Sdk\Response\Payload\Payloads\Paytabs;

class InvoiceStatus extends Paytabs
{
    public string $invoice_status; // "paid"

    public ?string $tran_ref; // "TST2104500076067"

    public ?string $tran_status; // "A"
    public TranStatus $tranStatus;

    public ?string $tran_status_msg; // "Authorised"

    public function setTranStatus(string $tranStatus): void
    {
        $this->tran_status = $tranStatus;
        $this->tranStatus = TranStatus::tryFrom(strtoupper($tranStatus));
    }
}
