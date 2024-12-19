<?php

namespace Request;

use Enums\HttpType;
use Gateway\Gateway;
use Helpers\Helpers;
use Holder\BuilderInterface;

class Request
{
    protected Gateway $environment;
    protected BuilderInterface $dataHolder;

    protected string $path;

    protected HttpType $httpType = HttpType::POST;

    //

    public function __construct(
        Gateway $environment,
        BuilderInterface $holder,
        string $path = null,
    ) {
        $this->environment = $environment;
        $this->dataHolder = $holder;
        if ($path) {
            $this->path = $path;
        }
    }

    //

    public function getPayload(): array|string
    {
        $payload = array_merge(
            $this->environment->getBody(),
            $this->dataHolder->getPayload()->getBody(),
        );

        return json_encode($payload);
    }

    public function getHeaders(): array
    {
        return $this->environment->getHeaders();
    }

    public function getUrl(): string
    {
        return Helpers::urlBuild(
            $this->environment->getUrl(),
            $this->path
        );
    }

    public function getHttpType(): HttpType
    {
        return $this->httpType;
    }

    public function isHttpPost(): bool
    {
        return $this->getHttpType() == HttpType::POST;
    }
}
