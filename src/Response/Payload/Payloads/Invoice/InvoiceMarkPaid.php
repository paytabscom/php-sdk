<?php

namespace Paytabs\Sdk\Response\Payload\Payloads\Invoice;

use Paytabs\Sdk\Response\Payload\Payloads\Paytabs;

class InvoiceMarkPaid extends Paytabs
{
    public string $profile_id;

    public string $invoice_id;

    public string $invoice_currency;
    public string $invoice_total;

    public string $pay_method;
    public string $pay_description;

    public string $tran_ref;
}
