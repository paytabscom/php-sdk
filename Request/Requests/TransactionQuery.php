<?php

namespace Request\Requests;

use Request\PaytabsRequest;
use Response\Payload\Payment\Completed;

class TransactionQuery extends PaytabsRequest
{
    protected string $path = 'payment/query';

    protected string $responseClass = Completed::class;
}
