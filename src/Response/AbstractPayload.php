<?php

namespace Paytabs\Sdk\Response;

use Paytabs\Sdk\Helpers\Helpers;
use PHPUnit\Util\InvalidJsonException;

abstract class AbstractPayload implements PayloadInterface
{
    protected mixed $payload;

    //

    public function setResponseData(string|array $data): static
    {
        if (!\is_array($data)) {
            if (!Helpers::jsonValidate($data)) {
                throw new InvalidJsonException('Invalid Payload JSON data');
            }
        }

        $this->payload = $data;

        return $this;
    }

    public function getResponseData(): string|array
    {
        return $this->payload;
    }

    //

    abstract public function getMapped(): static;

    public function getMappedAs(PayloadInterface $class): PayloadInterface
    {
        $class->setResponseData($this->getResponseData());
        return $class->getMapped();
    }

    //

    public function getAsJson(): object|array
    {
        $data = $this->payload;

        if (\is_array($this->payload)) {
            $data = json_encode($this->payload);
        }

        return json_decode($data, false);
    }
}
