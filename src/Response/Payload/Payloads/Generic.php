<?php

namespace Paytabs\Sdk\Response\Payload\Payloads;

use Paytabs\Sdk\Response\Payload\AbstractPayload;

class Generic extends AbstractPayload
{
    public $payloadJson;

    public function getMapped(): static
    {
        $this->payloadJson = $this->getAsJson();

        return $this;
    }
}
