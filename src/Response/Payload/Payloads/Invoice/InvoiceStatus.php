<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Payload\Payloads\Invoice;

use Paytabs\Sdk\Enums\InvoiceStatus as EnumsInvoiceStatus;
use Paytabs\Sdk\Enums\TranStatus;
use Paytabs\Sdk\Response\Payload\Payloads\Paytabs;

class InvoiceStatus extends Paytabs
{
    public string $invoice_status; // "paid"
    public EnumsInvoiceStatus $invoiceStatus;

    public ?string $tran_ref; // "TST2104500076067"

    public ?string $tran_status; // "A"
    public TranStatus $tranStatus;

    public ?string $tran_status_msg; // "Authorised"

    public function setTranStatus(string $tranStatus): void
    {
        $this->tran_status = $tranStatus;
        $this->tranStatus = TranStatus::tryFrom(strtoupper($tranStatus));
    }

    public function setInvoiceStatus(string $invoiceStatus): void
    {
        $this->invoice_status = $invoiceStatus;
        $this->invoiceStatus = EnumsInvoiceStatus::tryFrom(strtolower($invoiceStatus)) ?? EnumsInvoiceStatus::Unknown;
    }
}
