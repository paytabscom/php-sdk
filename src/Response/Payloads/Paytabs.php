<?php

namespace Paytabs\Sdk\Response\Payloads;

use Paytabs\Sdk\Response\AbstractPayload;

abstract class Paytabs extends AbstractPayload
{
    public string $trace;

    public function getMapped(): static
    {
        $jsonMapper = new \JsonMapper();

        return $jsonMapper->map($this->getAsJson(), $this);
    }
}
