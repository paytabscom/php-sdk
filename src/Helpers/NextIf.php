<?php

namespace Paytabs\Sdk\Helpers;

interface NextIf
{
    public function nextIf(bool $cond);

    public function nextSkipIf(bool $cond);

    public function readNextIf(): ?bool;
}
