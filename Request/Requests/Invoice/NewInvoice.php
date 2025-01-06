<?php

namespace Request\Requests\Invoice;

use Gateway\Gateway;
use Holder\Builders\Invoice\Invoice;
use Request\PaytabsRequest;

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
}
