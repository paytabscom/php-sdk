<?php

namespace Paytabs\Sdk\Response\Payloads;

use Paytabs\Sdk\Response\PayloadInterface;

class Generic implements PayloadInterface
{
    protected ?string $response;

    public $json;

    //

    public function __construct(?string $response = null)
    {
        $this->response = $response;
    }

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
