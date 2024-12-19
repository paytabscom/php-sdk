<?php

namespace Holder\Builders\Token;

use Holder\Builders\AbstractHolder;
use Holder\Parts\Token\Token as TokenPart;


class Token extends AbstractHolder
{
    public function setToken(string $token)
    {
        $this->product->buildBody(
            new TokenPart($token)
        );

        return $this;
    }
}
