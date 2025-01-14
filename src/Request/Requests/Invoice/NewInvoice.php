<?php

namespace Paytabs\Sdk\Request\Requests\Invoice;

use Paytabs\Sdk\Gateway\Gateway;
use Paytabs\Sdk\Holder\Builders\Invoice\Invoice;
use Paytabs\Sdk\Request\PaytabsRequest;
use Paytabs\Sdk\Response\PayloadInterface;
use Paytabs\Sdk\Response\Payloads\Invoice\NewInvoice as NewInvoiceResponse;

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
        return new NewInvoiceResponse();
    }
}
