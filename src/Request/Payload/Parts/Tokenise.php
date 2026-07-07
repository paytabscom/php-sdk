<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Payload\Parts;

class Tokenise extends AbstractPart
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
            'show_save_card' => $this->isOptional,
        ];
    }
}
