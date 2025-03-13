<?php

namespace Paytabs\Sdk\Request\Requests;

use Paytabs\Sdk\Gateway\Gateway;
use Paytabs\Sdk\Request\Payload\Payloads\PaymentRequest as PayloadsPaymentRequest;
use Paytabs\Sdk\Request\PaytabsRequest;
use Paytabs\Sdk\Response\Payload\PayloadInterface;
use Paytabs\Sdk\Response\Payload\Payloads\Payment\Completed;

class PaymentRequest extends PaytabsRequest
{
    protected string $path = 'payment/request';

    public function __construct(
        Gateway $environment,
        PayloadsPaymentRequest $holder
    ) {
        parent::__construct($environment, $holder);
    }

    /** @return Completed */
    public function getResponseClass(): PayloadInterface
    {
        return new Completed();
    }
}
