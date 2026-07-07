<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Payload\Parts;

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
