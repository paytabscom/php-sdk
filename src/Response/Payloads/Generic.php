<?php

namespace Paytabs\Sdk\Response\Payloads;

use Paytabs\Sdk\Response\AbstractPayload;

class Generic extends AbstractPayload
{
    public $payloadJson;

    public function getMapped()
    {
        $this->payloadJson = $this->getAsJson();

        return $this;
    }
}
