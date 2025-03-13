<?php

namespace Paytabs\Sdk\Response\Payload\Payloads;

use Paytabs\Sdk\Response\Payload\AbstractPayload;

abstract class Paytabs extends AbstractPayload
{
    public string $trace;

    public function getMapped(): static
    {
        $jsonMapper = new \JsonMapper();

        return $jsonMapper->map($this->getAsJson(), $this);
    }
}
