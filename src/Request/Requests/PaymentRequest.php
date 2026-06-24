<?php

namespace Paytabs\Sdk\Request\Requests;

use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Request\Payload\Payloads\PaymentRequest as PayloadsPaymentRequest;
use Paytabs\Sdk\Request\PaytabsRequest;
use Paytabs\Sdk\Response\Payload\PayloadInterface;
use Paytabs\Sdk\Response\Payload\Payloads\Payment\Completed;

class PaymentRequest extends PaytabsRequest
{
    protected string $path = 'payment/request';

    public function __construct(
        Profile $profile,
        PayloadsPaymentRequest $holder
    ) {
        parent::__construct($profile, $holder);
    }

    /** @return Completed */
    public function getResponseClass(): PayloadInterface
    {
        return new Completed();
    }
}
