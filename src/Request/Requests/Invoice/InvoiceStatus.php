<?php

namespace Paytabs\Sdk\Request\Requests\Invoice;

use Paytabs\Sdk\Enums\HttpType;
use Paytabs\Sdk\Gateway\Gateway;
use Paytabs\Sdk\Holder\Builders\Invoice\InvoiceStatus as BuilderInvoiceStatus;
use Paytabs\Sdk\Request\PaytabsRequest;
use Paytabs\Sdk\Response\PayloadInterface;
use Paytabs\Sdk\Response\Payloads\Invoice\InvoiceStatus as ResponseInvoiceStatus;

class InvoiceStatus extends PaytabsRequest
{
    protected string $path = 'payment/invoice/status';

    protected HttpType $httpType = HttpType::POST;

    public function __construct(
        Gateway $environment,
        BuilderInvoiceStatus $holder
    ) {
        parent::__construct($environment, $holder);
    }

    /** @return InvoiceStatus */
    public function getResponseClass(): PayloadInterface
    {
        return new ResponseInvoiceStatus();
    }
}
