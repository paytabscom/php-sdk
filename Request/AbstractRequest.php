<?php

namespace Request;

use Enums\HttpType;
use Gateway\Gateway;
use Helpers\Helpers;
use Holder\BuilderInterface;
use Holder\PayloadInterface;
use Response\PayloadInterface as ResponsePayloadInterface;

abstract class AbstractRequest implements RequestInterface
{
    protected Gateway $environment;
    protected BuilderInterface $dataHolder;

    protected string $path;

    protected HttpType $httpType = HttpType::POST;

    protected ResponsePayloadInterface $responseClass;

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
        /** @var PayloadInterface */
        $dataPayload = $this->dataHolder->getPayload();

        $payload = array_merge(
            $this->environment->getBody(),
            $dataPayload->getBody(),
        );

        return json_encode($payload);
    }

    public function getHeaders(): array
    {
        /** @var PayloadInterface */
        $dataPayload = $this->dataHolder->getPayload();

        $headers = array_merge(
            $this->environment->getHeaders(),
            $dataPayload->getHeaders(),
        );

        return $headers;
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

    //

    public function getResponseClass(): ResponsePayloadInterface
    {
        return $this->responseClass->init();
    }
}
