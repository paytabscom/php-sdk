<?php

namespace Paytabs\Sdk\Holder\Parts;

class GenericPart extends AbstractPart
{
    private array $params;

    public function __construct(
        array $params
    ) {
        $this->params = $params;
    }

    public function build(): array
    {
        return $this->params;
    }
}
