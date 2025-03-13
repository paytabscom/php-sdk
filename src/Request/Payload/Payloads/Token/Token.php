<?php

namespace Paytabs\Sdk\Request\Payload\Payloads\Token;

use Paytabs\Sdk\Request\Payload\Payloads\AbstractHolder;
use Paytabs\Sdk\Request\Payload\Parts\Token as TokenPart;

class Token extends AbstractHolder
{
    public function buildToken(string $token)
    {
        $this->product->buildBody(
            new TokenPart($token)
        );

        return $this;
    }
}
