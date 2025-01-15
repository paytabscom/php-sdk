<?php

namespace Paytabs\Sdk\Request;

use Paytabs\Sdk\Enums\HttpType;
use Paytabs\Sdk\Gateway\Gateway;
use Paytabs\Sdk\Helpers\Helpers;
use Paytabs\Sdk\Holder\BuilderInterface;
use Paytabs\Sdk\Holder\PayloadInterface;
use Paytabs\Sdk\Response\PayloadInterface as ResponsePayloadInterface;

abstract class AbstractRequest implements RequestInterface
{
    protected Gateway $environment;
    protected BuilderInterface $dataHolder;

    protected string $path;

    // There are some params that could be fitted in the URL itself as part of the path
    // Sample: secure.paytabs.com/invoice/{invoice_id}/status
    protected bool $hasPathParams = false;

    protected HttpType $httpType = HttpType::POST;

    protected ?ResponsePayloadInterface $responseClass = null;

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
        $fullUrl = Helpers::urlBuild(
            $this->environment->getUrl(),
            $this->path
        );

        if ($this->hasPathParams) {
            $pathParams = $this->dataHolder->getPayload()->getPath();
            if (!empty($pathParams)) {
                $fullUrl = str_replace(array_keys($pathParams), array_values($pathParams), $fullUrl);
            }
        }

        return $fullUrl;
    }

    public function getHttpType(): HttpType
    {
        return $this->httpType;
    }

    public function isHttpPost(): bool
    {
        return $this->getHttpType() === HttpType::POST;
    }

    //

    public function getResponseClass(): ?ResponsePayloadInterface
    {
        return $this->responseClass;
    }
}
