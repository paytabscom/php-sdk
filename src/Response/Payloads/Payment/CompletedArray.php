<?php

namespace Paytabs\Sdk\Response\Payloads\Payment;

use Paytabs\Sdk\Response\Payloads\Payment;

class CompletedArray extends Payment
{
    /** @var array[Completed] */
    public array $transactions = [];

    public function getMapped()
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
