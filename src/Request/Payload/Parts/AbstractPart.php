<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Payload\Parts;

use Paytabs\Sdk\Helpers\NextIfInterface;
use Paytabs\Sdk\Request\Payload\PartInterface;

abstract class AbstractPart implements PartInterface, NextIfInterface
{
    private ?bool $nextIf = null;

    public function nextIf(bool $cond): static
    {
        $this->nextIf = $cond;

        return $this;
    }

    public function nextSkipIf(bool $cond): static
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
