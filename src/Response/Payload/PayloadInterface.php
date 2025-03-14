<?php

namespace Paytabs\Sdk\Response\Payload;

interface PayloadInterface
{
    public function setResponseData(string $data);

    public function getResponseData(): string;

    public function getMapped();

    public function getMappedAs(PayloadInterface $class): PayloadInterface;

    public function getAsJson();

    public function unMappedData(): array;
}
