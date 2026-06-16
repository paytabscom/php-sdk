<?php

namespace Paytabs\Sdk\Request\Payload\Payloads\Invoice;

use Paytabs\Sdk\Request\Payload\Parts\GenericPart;
use Paytabs\Sdk\Request\Payload\Paytabs\PaytabsBuilder;

class InvoiceSms extends PaytabsBuilder
{
    public function buildInvoiceId(string $invoiceId)
    {
        $this->product->buildPath(new GenericPart(
            [
                '{invoice_id}' => $invoiceId,
            ]
        ));

        return $this;
    }

    public function buildInvoiceSmsBody(string $phone)
    {
        $this->product->buildBody(new GenericPart(
            [
                'customer_details' => [
                    'phone' => $phone,
                ],
            ]
        ));
    }
}
