<?php

namespace Paytabs\Sdk\Response\Payload;

use Paytabs\Sdk\Helpers\Helpers;

abstract class AbstractPayload implements PayloadInterface
{
    private mixed $payloadRaw;

    public function setResponseData(array|string $data): static
    {
        if (!\is_array($data)) {
            if (!Helpers::jsonValidate($data)) {
                throw new \JsonException('Invalid Payload JSON data');
            }
        }

        $this->payloadRaw = $data;

        return $this;
    }

    public function getResponseData(): array|string
    {
        return $this->payloadRaw;
    }

    abstract public function getMapped(): static;

    public function getMappedAs(PayloadInterface $class): PayloadInterface
    {
        $class->setResponseData($this->getResponseData());

        return $class->getMapped();
    }

    public function getAsJson(): array|object
    {
        $data = $this->payloadRaw;

        if (\is_array($this->payloadRaw)) {
            $data = json_encode($this->payloadRaw);
        }

        return json_decode($data, false);
    }

    public function unMappedData(): array
    {
        if (null === $this->payloadRaw) {
            throw new \Exception('Payload data is missed');
        }
        $json = json_decode($this->payloadRaw, true);

        $arr = [];

        foreach ($json as $key => $value) {
            if (!isset($this->{$key})) {
                $arr[] = $key;
            } elseif (\is_object($this->{$key})) {
                // check missing nested data
            }
        }

        return $arr;
    }
}
