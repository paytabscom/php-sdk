<?php

namespace Request\Requests;

use Request\PaytabsRequest;
use Response\Payload\Payment\Completed;
use Response\PayloadInterface;

class TokenQuery extends PaytabsRequest
{
    protected string $path = 'payment/token';

    //

    /** @return Completed */
    public function getResponseClass(): PayloadInterface
    {
        return new Completed();
    }
}
