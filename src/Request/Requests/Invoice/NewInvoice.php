<?php

namespace Paytabs\Sdk\Request\Requests\Invoice;

use Gateway\Gateway;
use Holder\Builders\Invoice\Invoice;
use Request\PaytabsRequest;
use Response\PayloadInterface;
use Response\Payloads\Invoice\NewInvoice as NewInvoiceResponse;

class NewInvoice extends PaytabsRequest
{
    protected string $path = 'payment/invoice/new';

    //

    public function __construct(
        Gateway $environment,
        Invoice $holder
    ) {
        parent::__construct($environment, $holder);
    }

    /** @return NewInvoiceResponse */
    public function getResponseClass(): PayloadInterface
    {
        return new NewInvoiceResponse;
    }
}
