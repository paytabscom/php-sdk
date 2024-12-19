<?php

namespace Request\Requests;

use Request\PaytabsRequest;

class TokenDelete extends PaytabsRequest
{
    protected string $path = 'payment/token/delete';
}
