<?php

namespace Paytabs\Sdk\Request\Requests\Invoice;

use Paytabs\Sdk\Enums\HttpType;
use Paytabs\Sdk\Gateway\Gateway;
use Paytabs\Sdk\Request\Payload\Payloads\Invoice\InvoiceStatusGet as PayloadsInvoiceStatusGet;
use Paytabs\Sdk\Request\PaytabsRequest;
use Paytabs\Sdk\Response\Payload\PayloadInterface;
use Paytabs\Sdk\Response\Payload\Payloads\Invoice\InvoiceStatus;

class InvoiceStatusGet extends PaytabsRequest
{
    protected string $path = 'payment/invoice/{invoice_id}/status';

    protected HttpType $httpType = HttpType::GET;

    protected bool $hasPathParams = true;

    public function __construct(
        Gateway $environment,
        PayloadsInvoiceStatusGet $holder
    ) {
        parent::__construct($environment, $holder);
    }

    /** @return InvoiceStatus */
    public function getResponseClass(): PayloadInterface
    {
        return new InvoiceStatus();
    }
}
