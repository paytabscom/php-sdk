<?php

namespace Holder\Parts;

use Holder\PartInterface;

class Urls implements PartInterface
{
    private ?string $returnUrl;
    private ?string $callbackUrl;

    public function __construct(
        ?string $returnUrl,
        ?string $callbackUrl
    ) {
        $this->returnUrl = $returnUrl;
        $this->callbackUrl = $callbackUrl;
    }

    public function build(): array
    {
        return [
            'return' => $this->returnUrl,
            'callback' => $this->callbackUrl,
        ];
    }
}
