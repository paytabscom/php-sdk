<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Payload\Parts;

use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;

class Transaction extends AbstractPart
{
    private TranType $tranType;
    private TranClass $tranClass = TranClass::Ecom;

    public function __construct(
        TranType $tranType,
        TranClass $tranClass = TranClass::Ecom
    ) {
        $this->tranType = $tranType;
        $this->tranClass = $tranClass;
    }

    public function build(): array
    {
        return [
            'tran_type' => $this->tranType->value,
            'tran_class' => $this->tranClass->value,
        ];
    }
}
