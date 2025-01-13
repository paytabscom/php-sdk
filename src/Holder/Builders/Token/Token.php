<?php

namespace Paytabs\Sdk\Holder\Builders\Token;

use Paytabs\Sdk\Holder\Builders\AbstractHolder;
use Paytabs\Sdk\Holder\Parts\Token\Token as TokenPart;

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
