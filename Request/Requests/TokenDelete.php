<?php

namespace Request\Requests;

use Request\PaytabsRequest;

class TokenDelete extends TokenQuery
{
    protected string $path = 'payment/token/delete';
}
