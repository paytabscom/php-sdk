<?php

namespace Paytabs\Sdk\Request\Payload\Parts;

use Paytabs\Sdk\Enums\InvoicePaidPayMethods;

class InvoiceMarkPaid extends AbstractPart
{
    private string $invoiceId;
    private string $invoiceCurrency;
    private float $invoiceTotal;
    private InvoicePaidPayMethods $payMethod;
    private string $payDescription;

    public function __construct(
        string $invoiceId,
        string $invoiceCurrency,
        float $invoiceTotal,
        InvoicePaidPayMethods $payMethod,
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
            'pay_method' => $this->payMethod,
            'pay_description' => $this->payDescription,
        ];
    }
}
