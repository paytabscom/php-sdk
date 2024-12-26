<?php

namespace Request\Requests;

use Request\PaytabsRequest;
use Response\Payload\Completed;

class TransactionQuery extends PaytabsRequest
{
    protected string $path = 'payment/query';

    protected array $expectedResponses = [
        Completed::class,
    ];
}
