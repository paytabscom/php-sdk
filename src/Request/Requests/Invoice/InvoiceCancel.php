<?php

namespace Paytabs\Sdk\Request\Requests\Invoice;

use Paytabs\Sdk\Enums\HttpType;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Request\Payload\Payloads\Invoice\InvoiceCancel as BuilderInvoiceCancel;
use Paytabs\Sdk\Request\PaytabsRequest;
use Paytabs\Sdk\Response\Payload\PayloadInterface;
use Paytabs\Sdk\Response\Payload\Payloads\Invoice\InvoiceCancel as ResponseInvoiceCancel;

class InvoiceCancel extends PaytabsRequest
{
    protected string $path = 'payment/invoice/cancel';

    protected HttpType $httpType = HttpType::POST;

    public function __construct(
        Profile $profile,
        BuilderInvoiceCancel $holder
    ) {
        parent::__construct($profile, $holder);
    }

    /** @return InvoiceCancel */
    public function getResponseClass(): PayloadInterface
    {
        return new ResponseInvoiceCancel();
    }
}
