<?php

namespace Request\Requests;

use Request\PaytabsRequest;
use Response\Payload\Completed;

class TokenQuery extends PaytabsRequest
{
    protected string $path = 'payment/token';

    protected array $expectedResponses = [
        Completed::class,
    ];
}
