<?php

namespace Paytabs\Sdk\Helpers;

interface NextIfInterface
{
    public function nextIf(bool $cond);

    public function nextSkipIf(bool $cond);

    public function readNextIf(): ?bool;
}
