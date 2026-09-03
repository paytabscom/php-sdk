<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request;

use Paytabs\Sdk\Enums\HttpType;
use Paytabs\Sdk\Exceptions\HttpRequestException;
use Paytabs\Sdk\Exceptions\InvalidConfigurationException;
use Paytabs\Sdk\Helpers\Helpers;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Request\Payload\BuilderInterface;
use Paytabs\Sdk\Request\Payload\PayloadInterface;
use Paytabs\Sdk\Response\Payload\PayloadInterface as ResponsePayloadInterface;

abstract class AbstractRequest implements RequestInterface
{
    protected Profile $profile;
    protected BuilderInterface $dataHolder;

    protected string $path;

    // There are some params that could be fitted in the URL itself as part of the path
    // Sample: secure.paytabs.com/invoice/{invoice_id}/status
    protected bool $hasPathParams = false;

    protected HttpType $httpType = HttpType::POST;

    protected ?ResponsePayloadInterface $responseClass = null;

    public function __construct(
        BuilderInterface $holder,
        ?Profile $profile,
        ?string $path = null
    ) {
        $this->dataHolder = $holder;
        if ($profile) {
            $this->profile = $profile;
        }
        if ($path) {
            $this->path = $path;
        }
    }

    public function isReady(): bool
    {
        return $this->isProfileSet();
    }

    public function isProfileSet(): bool
    {
        return isset($this->profile);
    }

    public function setProfile(Profile $profile): void
    {
        $this->profile = $profile;
    }

    public function getPayload(): array|string
    {
        /** @var PayloadInterface */
        $dataPayload = $this->dataHolder->getPayload();

        $payload = array_merge(
            $this->profile->getBody(),
            $dataPayload->getBody(),
        );

        $encoded = json_encode($payload);

        if (false === $encoded) {
            throw HttpRequestException::payloadNotEncodable(json_last_error_msg());
        }

        return $encoded;
    }

    public function getPayloadObject(): BuilderInterface
    {
        return $this->dataHolder;
    }

    public function getHeaders(): array
    {
        /** @var PayloadInterface */
        $dataPayload = $this->dataHolder->getPayload();

        return array_merge(
            $this->profile->getHeaders(),
            $dataPayload->getHeaders(),
        );
    }

    public function getUrl(): string
    {
        $fullUrl = Helpers::urlBuild(
            $this->profile->getUrl(),
            $this->path
        );

        if ($this->hasPathParams) {
            $pathParams = $this->dataHolder->getPayload()->getPath();
            if (!empty($pathParams)) {
                $fullUrl = str_replace(
                    array_keys($pathParams),
                    array_map(static fn($value): string => rawurlencode((string) $value), array_values($pathParams)),
                    $fullUrl
                );
            }

            // Fail loudly rather than sending a request to a literal
            // `.../invoice/{invoice_id}/status`.
            if (preg_match('/\{[a-z_]+\}/i', $fullUrl, $matches)) {
                throw InvalidConfigurationException::missing(trim($matches[0], '{}'));
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
        return HttpType::POST === $this->getHttpType();
    }

    public function getResponseClass(): ?ResponsePayloadInterface
    {
        return $this->responseClass;
    }
}
