<?php

declare(strict_types=1);

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
        PayloadsInvoiceMarkPaid $holder,
        ?Profile $profile
    ) {
        parent::__construct($holder, $profile);
    }

    /** @return InvoiceMarkPaidResponse */
    public function getResponseClass(): PayloadInterface
    {
        return new InvoiceMarkPaidResponse();
    }
}
