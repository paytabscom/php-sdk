<?php

namespace Request\Requests;

use Gateway\Gateway;
use Holder\Builders\TransactionQuery as BuildersTransactionQuery;
use Request\PaytabsRequest;
use Response\Payloads\Payment\Completed;
use Response\PayloadInterface;

class TransactionQuery extends PaytabsRequest
{
    protected string $path = 'payment/query';

    //

    public function __construct(
        Gateway $environment,
        BuildersTransactionQuery $holder
    ) {
        parent::__construct($environment, $holder);
    }

    //

    /** @return Completed */
    public function getResponseClass(): PayloadInterface
    {
        return new Completed();
    }
}
