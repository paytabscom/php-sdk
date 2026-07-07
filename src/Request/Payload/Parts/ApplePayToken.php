<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Payload\Parts;

class ApplePayToken extends AbstractPart
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
