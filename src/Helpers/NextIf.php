<?php

namespace Helpers;

interface NextIf
{
    public function nextIf(bool $cond): self;

    public function nextSkipIf(bool $cond): self;

    public function readNextIf(): ?bool;
}
