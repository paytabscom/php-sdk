<?php

namespace Paytabs\Sdk\Holder\Parts;

use Paytabs\Sdk\Enums\TokenType;

class TokenEnhanced extends AbstractPart
{
    private string $token;
    private TokenType $tokenType;
    private ?int $counter = null;

    public function __construct(
        string $token,
        TokenType $tokenType,
        ?int $counter = null
    ) {
        $this->token = $token;
        $this->tokenType = $tokenType;
        $this->counter = $counter;

        if (!is_null($counter) && $counter < 1) {
            throw new \InvalidArgumentException('Counter must be greater than 0');
        }
    }

    public function build(): array
    {
        $_info = [
            'token' => $this->token,
            'token_type' => $this->tokenType->value,
        ];

        if (!is_null($this->counter)) {
            $_info['counter'] = $this->counter;
        }

        return [
            'token_info' => $_info,
        ];
    }
}
