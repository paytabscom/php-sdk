<?php

namespace Paytabs\Sdk\Response;

interface PayloadInterface
{
    public function setResponseData(array|string $data): static;

    public function getResponseData(): array|string;

    public function getMapped(): static;

    public function getMappedAs(PayloadInterface $class): PayloadInterface;

    public function getAsJson(): array|object;
}
