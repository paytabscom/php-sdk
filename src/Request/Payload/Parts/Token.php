<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Payload\Parts;

class Token extends AbstractPart
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
