<?php

namespace Paytabs\Sdk\Request;

use Paytabs\Sdk\Enums\HttpType;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Response\Payload\PayloadInterface;

interface RequestInterface
{
    public function isProfileSet(): bool;

    public function setProfile(Profile $profile): void;

    public function getUrl(): string;

    public function getHeaders(): array;

    public function getPayload(): array|string;

    public function getHttpType(): HttpType;

    public function isHttpPost(): bool;

    public function getResponseClass(): ?PayloadInterface;
}
