<?php

namespace Paytabs\Sdk\Response\Payload;

interface PayloadInterface
{
    public function setResponseData(array|string $data): static;

    public function getResponseData(): array|string;

    public function getMapped(): static;

    public function getMappedAs(self $class): self;

    public function getAsJson(): array|object;

    public function unMappedData(): array;
}
