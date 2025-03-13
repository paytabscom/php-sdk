<?php

namespace Paytabs\Sdk\Request\Requests\Invoice;

use Paytabs\Sdk\Gateway\Gateway;
use Paytabs\Sdk\Request\Payload\Payloads\Invoice\Invoice;
use Paytabs\Sdk\Request\PaytabsRequest;
use Paytabs\Sdk\Response\Payload\PayloadInterface;
use Paytabs\Sdk\Response\Payload\Payloads\Invoice\NewInvoice as NewInvoiceResponse;

class NewInvoice extends PaytabsRequest
{
    protected string $path = 'payment/invoice/new';

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
