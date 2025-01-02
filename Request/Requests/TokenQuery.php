<?php

namespace Request\Requests;

use Request\PaytabsRequest;
use Response\Payload\Payment\Completed;

class TokenQuery extends PaytabsRequest
{
    protected string $path = 'payment/token';

    protected string $responseClass = Completed::class;
}
