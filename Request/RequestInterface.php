<?php

namespace Request;

use Enums\HttpType;

interface RequestInterface
{
    public function getUrl(): string;

    public function getHeaders(): array;
    public function getPayload(): array|string;

    public function getHttpType(): HttpType;
    public function isHttpPost(): bool;

    public function getResponseClass(): string;
}
