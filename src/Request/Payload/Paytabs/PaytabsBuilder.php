<?php

namespace Paytabs\Sdk\Request\Payload\Paytabs;

use Paytabs\Sdk\Request\Payload\AbstractBuilder;

abstract class PaytabsBuilder extends AbstractBuilder
{
    public function __construct()
    {
        $this->product = new PaytabsPayload();
    }
}
