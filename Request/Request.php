<?php

namespace Request;

use Gateway\Gateway;
use Holder\BuilderInterface;

class Request
{
    private Gateway $environment;
    private BuilderInterface $dataHolder;
    private string $path;

    public function __construct(
        Gateway $environment,
        BuilderInterface $holder,
        string $path
    ) {
        $this->environment = $environment;
        $this->dataHolder = $holder;
        $this->path = $path;
    }

    //

    public function getPayload(): array
    {
        return array_merge(
            $this->environment->getBody(),
            $this->dataHolder->getPayload()->getBody(),
        );
    }

    public function getHeader(): array
    {
        return $this->environment->getHeaders();
    }

    public function getUrl(): string
    {
        return $this->environment->getUrl() . $this->path;
    }
}
