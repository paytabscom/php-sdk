<?php

namespace Paytabs\Sdk\Response\Payloads;

use Paytabs\Sdk\Response\AbstractPayload;

class Generic extends AbstractPayload
{
    public $payloadJson;

    public function getMapped(): static
    {
        $this->payloadJson = $this->getAsJson();

        return $this;
    }
}
