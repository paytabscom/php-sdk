<?php

namespace Holder\Builders;


use Holder\Parts\Airline;


abstract class AirlineData extends Root
{
    public function setAirlineData(string $pnrCode)
    {
        $this->product->buildBody(
            new Airline($pnrCode)
        );

        return $this;
    }
}
