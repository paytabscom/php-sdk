<?php

namespace Paytabs\Sdk\Holder\Parts;

use Paytabs\Sdk\Holder\Parts\AbstractPart;

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
