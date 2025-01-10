<?php

namespace Paytabs\Sdk\Holder\Parts;

use Holder\PartInterface;

class ApplePayToken implements PartInterface
{
    public string $applepayToken;

    public function __construct(
        string $applepayToken
    ) {
        $this->applepayToken = $applepayToken;
    }

    public function build(): array
    {
        return [
            'apple_pay_token' => $this->applepayToken,
        ];
    }
}
