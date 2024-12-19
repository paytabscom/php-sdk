<?php

namespace Request\Requests;

use Gateway\Gateway;
use Holder\BuilderInterface;
use Request\PaytabsRequest;

class PaymentRequest extends PaytabsRequest
{
    protected string $path = 'payment/request';

    public function __construct(
        Gateway $environment,
        BuilderInterface $holder
    ) {
        parent::__construct($environment, $holder);
    }
}
