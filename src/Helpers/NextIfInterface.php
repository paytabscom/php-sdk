<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Helpers;

interface NextIfInterface
{
    public function nextIf(bool $cond): static;

    public function nextSkipIf(bool $cond): static;

    public function readNextIf(): ?bool;
}
