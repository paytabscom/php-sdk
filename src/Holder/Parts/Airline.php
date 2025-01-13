<?php

namespace Paytabs\Sdk\Holder\Parts;

use Paytabs\Sdk\Holder\PartInterface;

class Airline implements PartInterface
{
    private string $pnrCode;

    public function __construct(
        string $pnrCode
    ) {
        $this->pnrCode = $pnrCode;
    }

    public function build(): array
    {
        return [
            'airline_data' => [
                'pnr_code' => $this->pnrCode,
            ],
        ];
    }
}
