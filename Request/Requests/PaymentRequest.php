<?php

namespace Request\Requests;

use Request\PaytabsRequest;
use Response\Payload\Payment\Completed;
use Response\Payload\Redirect;

class PaymentRequest extends PaytabsRequest
{
    protected string $path = 'payment/request';

    protected string $responseClass = Completed::class;
}
