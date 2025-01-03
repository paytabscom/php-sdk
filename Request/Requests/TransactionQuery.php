<?php

namespace Request\Requests;

use Request\PaytabsRequest;
use Response\Payload\Payment\Completed;
use Response\PayloadInterface;

class TransactionQuery extends PaytabsRequest
{
    protected string $path = 'payment/query';

    //

    /** @return Completed */
    public function getResponseClass(): PayloadInterface
    {
        return new Completed();
    }
}
