<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Payload\Parts;

use Paytabs\Sdk\Enums\InvoiceExternalPayMethod;

class InvoiceMarkPaid extends AbstractPart
{
    private int $invoiceId;
    private string $invoiceCurrency;
    private float $invoiceTotal;
    private InvoiceExternalPayMethod $payMethod;
    private string $payDescription;

    public function __construct(
        int $invoiceId,
        string $invoiceCurrency,
        float $invoiceTotal,
        InvoiceExternalPayMethod $payMethod,
        string $payDescription
    ) {
        $this->invoiceId = $invoiceId;
        $this->invoiceCurrency = $invoiceCurrency;
        $this->invoiceTotal = $invoiceTotal;
        $this->payMethod = $payMethod;
        $this->payDescription = $payDescription;
    }

    public function build(): array
    {
        return [
            'invoice_id' => $this->invoiceId,
            'invoice_currency' => $this->invoiceCurrency,
            'invoice_total' => $this->invoiceTotal,
            'pay_method' => $this->payMethod->value,
            'pay_description' => $this->payDescription,
        ];
    }
}
