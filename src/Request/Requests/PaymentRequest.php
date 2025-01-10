<?php

namespace Request\Requests;

use Gateway\Gateway;
use Holder\Builders\PaymentRequest as BuildersPaymentRequest;
use Request\PaytabsRequest;
use Response\Payloads\Payment\Completed;
use Response\PayloadInterface;

class PaymentRequest extends PaytabsRequest
{
    protected string $path = 'payment/request';

    //

    public function __construct(
        Gateway $environment,
        BuildersPaymentRequest $holder
    ) {
        parent::__construct($environment, $holder);
    }

    //

    /** @return Completed */
    public function getResponseClass(): PayloadInterface
    {
        return new Completed();
    }
}
