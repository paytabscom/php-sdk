<?php

namespace Response\Payloads;

use JsonMapper;
use Response\Payloads\Payment\Completed;

class CompletedArray extends Payment
{
    /** @var array[Completed] */
    public array $transactions = [];

    //

    public function fromJson($jsonResponse): self
    {
        $jsonMapper = new JsonMapper();

        $this->transactions = $jsonMapper->mapArray(
            $jsonResponse,
            [],
            Completed::class
        );

        return $this;
    }
}
