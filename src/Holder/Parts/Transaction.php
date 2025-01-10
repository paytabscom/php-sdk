<?php

namespace Holder\Parts;

use Enums\TranClass;
use Enums\TranType;
use Holder\PartInterface;

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
            'tran_type' => $this->tranType,
            'tran_class' => $this->tranClass,
        ];
    }
}
