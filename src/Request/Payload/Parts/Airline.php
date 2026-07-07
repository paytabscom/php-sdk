<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Payload\Parts;

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
