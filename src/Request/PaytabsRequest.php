<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request;

use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Request\Payload\BuilderInterface;

abstract class PaytabsRequest extends AbstractRequest
{
    public function __construct(
        BuilderInterface $holder,
        ?Profile $profile,
    ) {
        parent::__construct($holder, $profile);
    }

    public function getHeaders(): array
    {
        return $this->profile->getHeaders();
    }
}
