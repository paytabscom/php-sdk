<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Payload\Payloads;

use Paytabs\Sdk\Response\Payload\AbstractPayload;

class Generic extends AbstractPayload
{
    public array|object $payloadJson;

    public function getMapped(): static
    {
        $this->payloadJson = $this->getAsJson();

        return $this;
    }
}
