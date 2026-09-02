<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Payload\Payloads\Invoice;

use Paytabs\Sdk\Enums\InvoiceExternalPayMethod;
use Paytabs\Sdk\Response\Payload\Payloads\Paytabs;

class InvoiceMarkPaid extends Paytabs
{
    public ?int $profile_id = null;

    public ?int $invoice_id = null;

    public ?string $invoice_currency = null;
    public ?float $invoice_total = null;

    public ?string $pay_method = null;
    public ?InvoiceExternalPayMethod $payMethod = null;

    public ?string $pay_description = null;

    public ?string $tran_ref = null;

    public function setPayMethod(string $payMethod): void
    {
        $this->pay_method = $payMethod;
        $this->payMethod
            = InvoiceExternalPayMethod::tryFrom(strtolower($payMethod))
            ?? InvoiceExternalPayMethod::Unknown;
    }
}
