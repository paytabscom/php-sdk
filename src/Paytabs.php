<?php

declare(strict_types=1);

namespace Paytabs\Sdk;

use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Request\AbstractRequest;
use Paytabs\Sdk\Response\ResponseDirectInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Paytabs
{
    // Version
    public const VERSION = '3.0.0';

    protected Profile $profile;
    protected Http $http;
    protected LoggerInterface $logger;

    final public static function getVersion(): string
    {
        return self::VERSION;
    }

    public static function getInstance(
        Profile $profile,
        ?Http $http = null,
        ?LoggerInterface $logger = null
    ): self {
        $instance = new static();

        $instance->profile = $profile;
        $instance->http = $http ?? Http::create();

        $instance->setLogger($logger ?? new NullLogger());

        return $instance;
    }

    public function setRequest(AbstractRequest $request): self
    {
        if (!$request->isProfileSet()) {
            $request->setProfile($this->profile);
        }
        $this->http->setRequest($request);

        return $this;
    }

    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;
        $this->http->setLogger($logger);

        return $this;
    }

    public function getProfile(): Profile
    {
        return $this->profile;
    }

    public function submit(?ResponseDirectInterface $responseClass = null): ResponseDirectInterface
    {
        return $this->http->submit($responseClass);
    }
}
