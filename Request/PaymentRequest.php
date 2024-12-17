<?php

namespace Request;

use Gateway\Gateway;
use Holder\BuilderInterface;

class PaymentRequest extends Request
{
    protected string $path = 'payment/request';

    public function __construct(
        Gateway $environment,
        BuilderInterface $holder
    ) {
        parent::__construct($environment, $holder);
    }
}
