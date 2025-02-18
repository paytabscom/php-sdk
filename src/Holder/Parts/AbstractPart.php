<?php

namespace Paytabs\Sdk\Holder\Parts;

use Paytabs\Sdk\Helpers\NextIf;
use Paytabs\Sdk\Holder\PartInterface;

abstract class AbstractPart implements PartInterface, NextIf
{
    private ?bool $nextIf = null;

    public function nextIf(bool $cond)
    {
        $this->nextIf = $cond;

        return $this;
    }

    public function nextSkipIf(bool $cond)
    {
        return $this->nextIf(!$cond);
    }

    public function readNextIf(): ?bool
    {
        $next = $this->nextIf;

        $this->nextIf = null;

        return $next;
    }
}
