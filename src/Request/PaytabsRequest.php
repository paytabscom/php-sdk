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
        return array_merge(
            $this->profile->getHeaders(),
            [
                // The body is JSON, but cURL given a string CURLOPT_POSTFIELDS
                // defaults to application/x-www-form-urlencoded. Without this
                // the SDK relied on the gateway sniffing the body.
                'Content-Type: application/json',
                'Accept: application/json',
            ]
        );
    }
}
