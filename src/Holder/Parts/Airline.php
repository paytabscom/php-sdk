<?php

namespace Paytabs\Sdk\Holder\Parts;

class Airline extends AbstractPart
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
