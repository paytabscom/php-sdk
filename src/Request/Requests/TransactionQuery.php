<?php

namespace Paytabs\Sdk\Request\Requests;

use Paytabs\Sdk\Gateway\Gateway;
use Paytabs\Sdk\Holder\Builders\TransactionQuery as BuildersTransactionQuery;
use Paytabs\Sdk\Request\PaytabsRequest;
use Paytabs\Sdk\Response\Payloads\Payment\Completed;
use Paytabs\Sdk\Response\PayloadInterface;

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
