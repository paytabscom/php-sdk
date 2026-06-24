<?php

namespace Paytabs\Sdk\Request;

use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Request\Payload\BuilderInterface;

abstract class PaytabsRequest extends AbstractRequest
{
    public function __construct(
        Profile $profile,
        BuilderInterface $holder
    ) {
        parent::__construct($profile, $holder);
    }

    public function getHeaders(): array
    {
        return $this->profile->getHeaders();
    }
}
