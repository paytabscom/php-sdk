<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Payload\Parts;

class TransactionRef extends AbstractPart
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
