<?php

namespace Paytabs\Sdk\Holder\Parts;

use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Holder\PartInterface;

class Transaction implements PartInterface
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
