<?php

namespace Paytabs\Sdk\Request\Requests\Invoice;

use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Request\Payload\Payloads\Invoice\InvoiceMarkPaid as PayloadsInvoiceMarkPaid;
use Paytabs\Sdk\Request\PaytabsRequest;
use Paytabs\Sdk\Response\Payload\PayloadInterface;
use Paytabs\Sdk\Response\Payload\Payloads\Invoice\InvoiceMarkPaid as InvoiceMarkPaidResponse;

class InvoiceMarkPaid extends PaytabsRequest
{
    protected string $path = 'payment/invoice/paid';

    public function __construct(
        Profile $profile,
        PayloadsInvoiceMarkPaid $holder
    ) {
        parent::__construct($profile, $holder);
    }

    /** @return InvoiceMarkPaidResponse */
    public function getResponseClass(): PayloadInterface
    {
        return new InvoiceMarkPaidResponse();
    }
}
