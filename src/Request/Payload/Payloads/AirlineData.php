<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Payload\Payloads;

use Paytabs\Sdk\Request\Payload\Parts\Airline;

abstract class AirlineData extends PaymentRequest
{
    public function buildAirlineData(string $pnrCode)
    {
        $this->product->buildBody(
            new Airline($pnrCode)
        );

        return $this;
    }
}
