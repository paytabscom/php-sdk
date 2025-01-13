<?php

namespace Paytabs\Sdk\Holder\Parts;

use Paytabs\Sdk\Holder\PartInterface;

class Tokenise implements PartInterface
{
    private int $tokenFormat;
    private bool $isOptional;

    public function __construct(
        int $tokenFormat = 2,
        bool $isOptional = false
    ) {
        $this->tokenFormat = $tokenFormat;
        $this->isOptional = $isOptional;
    }

    public function build(): array
    {
        return [
            'tokenise' => $this->tokenFormat,
            'show_save_card' => $this->isOptional
        ];
    }
}
