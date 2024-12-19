<?php

namespace Request\Requests;

use Request\PaytabsRequest;

class TokenQuery extends PaytabsRequest
{
    protected string $path = 'payment/token';
}
