<?php

namespace Paytabs\Sdk\Helpers;

interface NextIf
{
    public function nextIf(bool $cond): static;

    public function nextSkipIf(bool $cond): static;

    public function readNextIf(): ?bool;
}
