<?php

namespace Response\Payloads;

use Response\PayloadInterface;

class Generic implements PayloadInterface
{
    public $json;

    //

    public function init(): self
    {
        return new self();
    }

    public function fromJson($jsonResponse): self
    {
        $this->json = $jsonResponse;

        return $this;
    }
}
