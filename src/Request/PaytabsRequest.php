<?php

namespace Paytabs\Sdk\Request;

use Paytabs\Sdk\Gateway\Gateway;
use Paytabs\Sdk\Request\Payload\BuilderInterface;

abstract class PaytabsRequest extends AbstractRequest
{
    public function __construct(
        Gateway $environment,
        BuilderInterface $holder
    ) {
        parent::__construct($environment, $holder);
    }

    public function getHeaders(): array
    {
        return $this->environment->getHeaders();
    }
}
