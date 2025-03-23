<?php

namespace Paytabs\Sdk\Request\Payload\Parts;

class InvoiceMarkPaid extends AbstractPart
{
    private string $profileId;
    private string $invoiceId;
    private string $invoiceCurrency;
    private float  $invoiceTotal;
    private string $payMethod;
    private string $payDescription;

    public function __construct(
        string $profileId,
        string $invoiceId,
        string $invoiceCurrency,
        float $invoiceTotal,
        string $payMethod,
        string $payDescription
    ) {
        $this->profileId = $profileId;
        $this->invoiceId = $invoiceId;
        $this->invoiceCurrency = $invoiceCurrency;
        $this->invoiceTotal = $invoiceTotal;
        $this->payMethod = $payMethod;
        $this->payDescription = $payDescription;
    }

    public function build(): array
    {
        return [
            'profile_id' => $this->profileId,
            'invoice_id' => $this->invoiceId,
            'invoice_currency' => $this->invoiceCurrency,
            'invoice_total' => $this->invoiceTotal,
            'pay_method' => $this->payMethod,
            'pay_description' => $this->payDescription,
        ];
    }
}
