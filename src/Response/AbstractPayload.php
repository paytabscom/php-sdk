<?php

namespace Paytabs\Sdk\Response;

use Paytabs\Sdk\Helpers\Helpers;
use PHPUnit\Util\InvalidJsonException;

abstract class AbstractPayload implements PayloadInterface
{
    private mixed $payloadRaw;

    //

    public function setResponseData(string $data): static
    {
        if (!\is_array($data)) {
            if (!Helpers::jsonValidate($data)) {
                throw new InvalidJsonException('Invalid Payload JSON data');
            }
        }

        $this->payloadRaw = $data;

        return $this;
    }

    public function getResponseData(): string
    {
        return $this->payloadRaw;
    }

    //

    abstract public function getMapped(): static;

    public function getMappedAs(PayloadInterface $class): PayloadInterface
    {
        $class->setResponseData($this->getResponseData());
        return $class->getMapped();
    }

    //

    public function getAsJson(): object
    {
        $data = $this->payloadRaw;

        if (\is_array($this->payloadRaw)) {
            $data = json_encode($this->payloadRaw);
        }

        return json_decode($data, false);
    }
}
