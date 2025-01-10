<?php

namespace Holder\Parts\Token;

use Holder\PartInterface;

class Token implements PartInterface
{
    private string $token;

    public function __construct(
        string $token
    ) {
        $this->token = $token;
    }

    public function build(): array
    {
        return [
            'token' => $this->token,
        ];
    }
}
