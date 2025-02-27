<?php

namespace Paytabs\Sdk\Holder\Parts;

use Paytabs\Sdk\Helpers\NextIf;
use Paytabs\Sdk\Helpers\NextIfInterface;
use Paytabs\Sdk\Holder\PartInterface;

abstract class AbstractPart implements PartInterface, NextIfInterface
{
    use NextIf;

    public static function init(): static
    {
        return new static();
    }

    public function nextIf(bool $cond): static
    {
        $this->nextIf = $cond;

        return $this;
    }

    public function nextSkipIf(bool $cond): static
    {
        return new static();
    }
}
