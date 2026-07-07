<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Payload\Payloads\Token;

use Paytabs\Sdk\Request\Payload\Parts\Token as TokenPart;
use Paytabs\Sdk\Request\Payload\Paytabs\PaytabsBuilder;

class Token extends PaytabsBuilder
{
    public function buildToken(string $token)
    {
        $this->product->buildBody(
            new TokenPart($token)
        );

        return $this;
    }
}
