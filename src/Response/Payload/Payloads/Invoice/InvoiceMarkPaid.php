<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Payload\Payloads\Invoice;

use Paytabs\Sdk\Enums\InvoiceExternalPayMethod;
use Paytabs\Sdk\Response\Payload\Payloads\Paytabs;

class InvoiceMarkPaid extends Paytabs
{
    public int $profile_id;

    public int $invoice_id;

    public string $invoice_currency;
    public float $invoice_total;

    public string $pay_method;
    public InvoiceExternalPayMethod $payMethod;

    public string $pay_description;

    public string $tran_ref;

    public function setPayMethod(string $payMethod): void
    {
        $this->pay_method = $payMethod;
        $this->payMethod = InvoiceExternalPayMethod::tryFrom(strtolower($payMethod));
    }
}
