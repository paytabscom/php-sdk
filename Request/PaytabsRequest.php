<?php

namespace Request;

use Gateway\Gateway;
use Holder\BuilderInterface;

abstract class PaytabsRequest extends AbstractRequest
{
    public function __construct(
        Gateway $environment,
        BuilderInterface $holder
    ) {
        parent::__construct($environment, $holder);
    }

    //

    public function getHeaders(): array
    {
        return $this->environment->getHeaders();
    }
}
