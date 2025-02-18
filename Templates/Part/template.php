<?php

namespace Paytabs\Sdk\Holder\Parts;

class __ClassName__ extends AbstractPart
{
    private $paramName;

    public function __construct($paramName)
    {
        $this->paramName = $paramName;
    }

    public function build(): array
    {
        return [
            'param_name' => $this->paramName,
        ];
    }
}
