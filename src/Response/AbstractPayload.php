<?php

namespace Paytabs\Sdk\Response;

use JsonException;
use Paytabs\Sdk\Helpers\Helpers;

abstract class AbstractPayload implements PayloadInterface
{
    private $payloadRaw;

    //

    public function setResponseData(string $data)
    {
        if (!\is_array($data)) {
            if (!Helpers::jsonValidate($data)) {
                throw new JsonException('Invalid Payload JSON data');
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

    abstract public function getMapped();

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
