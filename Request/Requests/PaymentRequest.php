<?php

namespace Request\Requests;

use Request\PaytabsRequest;
use Response\Payload\Payment\Completed;
use Response\PayloadInterface;

class PaymentRequest extends PaytabsRequest
{
    protected string $path = 'payment/request';

    //

    /** @return Completed */
    public function getResponseClass(): PayloadInterface
    {
        return new Completed();
    }
}
