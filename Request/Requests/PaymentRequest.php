<?php

namespace Request\Requests;

use Request\PaytabsRequest;

class PaymentRequest extends PaytabsRequest
{
    protected string $path = 'payment/request';
}
