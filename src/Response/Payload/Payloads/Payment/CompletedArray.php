<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Payload\Payloads\Payment;

use Paytabs\Sdk\Response\Payload\Payloads\Payment;

class CompletedArray extends Payment
{
    /** @var Completed[] */
    public array $transactions = [];

    public function getMapped(): static
    {
        $jsonMapper = new \JsonMapper();

        $this->transactions = $jsonMapper->mapArray(
            $this->getAsJson(),
            [],
            Completed::class
        );

        return $this;
    }
}
