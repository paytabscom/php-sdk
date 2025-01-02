<?php

namespace Request\Requests;

use Request\PaytabsRequest;
use Response\Payload\Payment\Completed;

class TokenQuery extends PaytabsRequest
{
    protected string $path = 'payment/token';

    protected array $expectedResponses = [
        Completed::class,
    ];
}
