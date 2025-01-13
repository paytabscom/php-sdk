<?php

namespace Paytabs\Sdk\Holder\Parts;

use Paytabs\Sdk\Holder\PartInterface;

class TransactionRef implements PartInterface
{
    private string $tranRef;

    public function __construct(
        string $tranRef
    ) {
        $this->tranRef = $tranRef;
    }

    public function build(): array
    {
        return [
            'tran_ref' => $this->tranRef,
        ];
    }
}
