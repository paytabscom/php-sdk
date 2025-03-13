<?php

namespace Paytabs\Sdk\Request\Requests;

use Paytabs\Sdk\Gateway\Gateway;
use Paytabs\Sdk\Request\Payload\Payloads\TransactionQuery as PayloadsTransactionQuery;
use Paytabs\Sdk\Request\PaytabsRequest;
use Paytabs\Sdk\Response\Payload\PayloadInterface;
use Paytabs\Sdk\Response\Payload\Payloads\Payment\Completed;

class TransactionQuery extends PaytabsRequest
{
    protected string $path = 'payment/query';

    public function __construct(
        Gateway $environment,
        PayloadsTransactionQuery $holder
    ) {
        parent::__construct($environment, $holder);
    }

    /** @return Completed */
    public function getResponseClass(): PayloadInterface
    {
        return new Completed();
    }
}
