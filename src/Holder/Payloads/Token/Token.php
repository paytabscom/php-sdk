<?php

namespace Paytabs\Sdk\Holder\Payloads\Token;

use Paytabs\Sdk\Holder\Payloads\AbstractHolder;
use Paytabs\Sdk\Holder\Parts\Token as TokenPart;

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
