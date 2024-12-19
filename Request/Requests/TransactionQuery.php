<?php

namespace Request\Requests;

use Request\PaytabsRequest;

class TransactionQuery extends PaytabsRequest
{
    protected string $path = 'payment/query';
}
