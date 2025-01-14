<?php

namespace Paytabs\Sdk\Holder\Builders;

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
