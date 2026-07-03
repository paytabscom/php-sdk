<?php

namespace Paytabs\Sdk\Request\Requests;

use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Request\Payload\Payloads\TransactionQuery as PayloadsTransactionQuery;
use Paytabs\Sdk\Request\PaytabsRequest;
use Paytabs\Sdk\Response\Payload\PayloadInterface;
use Paytabs\Sdk\Response\Payload\Payloads\Payment\Completed;
use Paytabs\Sdk\Response\Payload\Payloads\Payment\CompletedArray;

class TransactionQuery extends PaytabsRequest
{
    protected string $path = 'payment/query';

    public function __construct(
        PayloadsTransactionQuery $holder,
        ?Profile $profile,
    ) {
        parent::__construct($holder, $profile);
    }

    /** @return Completed|CompletedArray */
    public function getResponseClass(): PayloadInterface
    {
        return $this->getPayloadObject()->getResponseClass();
    }
}
