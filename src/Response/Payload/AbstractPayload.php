<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Payload;

use Paytabs\Sdk\Helpers\Helpers;

abstract class AbstractPayload implements PayloadInterface
{
    private string $payloadRaw;

    public function setResponseData(string $data): static
    {
        if (!Helpers::jsonValidate($data)) {
            throw new \JsonException('Invalid Payload JSON data');
        }

        $this->payloadRaw = $data;

        return $this;
    }

    public function getResponseData(): string
    {
        return $this->payloadRaw;
    }

    /**
     * @throws \InvalidArgumentException if the payload is not valid or cannot be mapped.
     * @return static
     */
    abstract public function getMapped(): static;

    /**
     * @throws \InvalidArgumentException if the payload is not valid or cannot be mapped.
     * @return PayloadInterface
     */
    public function getMappedAs(PayloadInterface $class): PayloadInterface
    {
        $class->setResponseData($this->getResponseData());

        return $class->getMapped();
    }

    public function getAsJson(): array|object
    {
        $data = $this->payloadRaw;

        return json_decode($data, false);
    }

    public function unMappedData(): array
    {
        if (empty($this->payloadRaw)) {
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
