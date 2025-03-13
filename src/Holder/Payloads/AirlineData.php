<?php

namespace Paytabs\Sdk\Holder\Payloads;

use Paytabs\Sdk\Holder\Parts\Airline;

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
