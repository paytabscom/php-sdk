<?php

namespace Paytabs\Sdk\Response\Payload\Payloads\Invoice;

use Paytabs\Sdk\Response\Payload\Payloads\Paytabs;

class InvoiceMarkPaid extends Paytabs
{
    public ?string $code;

    public ?string $message;

    public string $trace;

    public ?string $profile_id;
    public ?string $invoice_id;
    public ?string $invoice_currency;
    public ?string $invoice_total;
    public ?string $pay_method;
    public ?string $pay_description;
    public ?string $tran_ref;

    // success
    // "profile_id": 59314,
    // "invoice_id": 1394474,
    // "invoice_currency": "SAR",
    // "invoice_total": "9900.00",
    // "pay_method": "Cheque",
    // "pay_description": "Cheque number 1234",
    // "tran_ref": "TST2508301021523"

    // error
    // "code": 2,
    // "message": "Invoice already paid",
    // "trace": "PMNT0202.67E1E963.00019454"
}
