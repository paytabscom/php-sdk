<?php

namespace Paytabs\Sdk\Request;

use Enums\HttpType;
use Response\PayloadInterface;

interface RequestInterface
{
    public function getUrl(): string;

    public function getHeaders(): array;
    public function getPayload(): array|string;

    public function getHttpType(): HttpType;
    public function isHttpPost(): bool;

    public function getResponseClass(): ?PayloadInterface;
}
