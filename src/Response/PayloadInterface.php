<?php

namespace Paytabs\Sdk\Response;

interface PayloadInterface
{
    public function setResponseData(string|array $data): static;

    public function getResponseData(): string|array;

    //

    public function getMapped(): static;

    public function getMappedAs(PayloadInterface $class): PayloadInterface;

    //

    public function getAsJson(): object|array;
}
