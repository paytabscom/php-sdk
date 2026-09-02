<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Payload\Payloads\Invoice;

use Paytabs\Sdk\Enums\InvoiceStatus as EnumsInvoiceStatus;
use Paytabs\Sdk\Enums\TranStatus;
use Paytabs\Sdk\Response\Payload\Payloads\Paytabs;

class InvoiceStatus extends Paytabs
{
    public ?string $invoice_status = null; // "paid"
    public ?EnumsInvoiceStatus $invoiceStatus = null;

    public ?string $tran_ref = null; // "TST2104500076067"

    public ?string $tran_status = null; // "A"
    public ?TranStatus $tranStatus = null;

    public ?string $tran_status_msg = null; // "Authorised"

    /**
     * ?string, because an unpaid or pending invoice legitimately reports
     * `tran_status: null`.
     */
    public function setTranStatus(?string $tranStatus): void
    {
        $this->tran_status = $tranStatus;

        // ?? Unknown, matching every other enum setter in the SDK: tryFrom()
        // returns null for an unrecognised status letter, which the typed
        // property then refused to accept.
        $this->tranStatus = null === $tranStatus
            ? null
            : TranStatus::tryFrom(strtoupper($tranStatus)) ?? TranStatus::Unknown;
    }

    public function setInvoiceStatus(string $invoiceStatus): void
    {
        $this->invoice_status = $invoiceStatus;
        $this->invoiceStatus = EnumsInvoiceStatus::tryFrom(strtolower($invoiceStatus)) ?? EnumsInvoiceStatus::Unknown;
    }
}
