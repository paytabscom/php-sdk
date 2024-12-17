<?php

namespace Request;

use Gateway\Gateway;
use Holder\BuilderInterface;

class Request
{
    protected Gateway $environment;
    protected BuilderInterface $dataHolder;
    protected string $path;

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

    public function getHeader(): array
    {
        return $this->environment->getHeaders();
    }

    public function getUrl(): string
    {
        $domain = rtrim($this->environment->getUrl(), '/');
        return $domain . '/' . $this->path;
    }
}
