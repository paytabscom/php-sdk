<?php

namespace Paytabs\Sdk\Request\Requests;

use Paytabs\Sdk\Gateway\Gateway;
use Paytabs\Sdk\Holder\Builders\PaymentRequest as BuildersPaymentRequest;
use Paytabs\Sdk\Request\PaytabsRequest;
use Paytabs\Sdk\Response\PayloadInterface;
use Paytabs\Sdk\Response\Payloads\Payment\Completed;

class PaymentRequest extends PaytabsRequest
{
    protected string $path = 'payment/request';

    public function __construct(
        Gateway $environment,
        BuildersPaymentRequest $holder
    ) {
        parent::__construct($environment, $holder);
    }

    /** @return Completed */
    public function getResponseClass(): PayloadInterface
    {
        return new Completed();
    }
}
