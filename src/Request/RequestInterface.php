<?php

namespace Paytabs\Sdk\Request;

use Paytabs\Sdk\Enums\HttpType;
use Paytabs\Sdk\Response\PayloadInterface;

interface RequestInterface
{
    public function getUrl(): string;

    public function getHeaders(): array;
    public function getPayload(): string;

    public function getHttpType(): HttpType;
    public function isHttpPost(): bool;

    public function getResponseClass(): ?PayloadInterface;
}
