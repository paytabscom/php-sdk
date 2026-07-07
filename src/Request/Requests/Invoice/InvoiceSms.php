<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Requests\Invoice;

use Paytabs\Sdk\Enums\HttpType;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Request\Payload\Payloads\Invoice\InvoiceSms as BuilderInvoiceSms;
use Paytabs\Sdk\Request\PaytabsRequest;
use Paytabs\Sdk\Response\Payload\PayloadInterface;
use Paytabs\Sdk\Response\Payload\Payloads\Invoice\InvoiceSms as ResponseInvoiceSms;

class InvoiceSms extends PaytabsRequest
{
    protected string $path = 'payment/invoice/{invoice_id}/sms';

    protected HttpType $httpType = HttpType::POST;

    protected bool $hasPathParams = true;

    public function __construct(
        BuilderInvoiceSms $holder,
        ?Profile $profile,
    ) {
        parent::__construct($holder, $profile);
    }

    /** @return ResponseInvoiceSms */
    public function getResponseClass(): PayloadInterface
    {
        return new ResponseInvoiceSms();
    }
}
