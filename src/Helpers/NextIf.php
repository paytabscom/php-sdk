<?php

namespace Paytabs\Sdk\Helpers;

trait NextIf
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
