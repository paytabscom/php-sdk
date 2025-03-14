<?php

namespace Paytabs\Sdk\Request\Payload\Paytabs;

use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Payload\AbstractBuilder;
use Paytabs\Sdk\Request\Payload\Paytabs\PaytabsPayload;

abstract class PaytabsBuilder extends AbstractBuilder
{
    public function __construct()
    {
        $this->product = new PaytabsPayload();

        $this->logger = Paytabs::getLogger();
    }
}
