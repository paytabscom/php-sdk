<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Profile;

use Paytabs\Sdk\Exceptions\InvalidConfigurationException;
use Paytabs\Sdk\Logger\Redactor;
use Paytabs\Sdk\Request\Payload\AbstractPayload;
use Paytabs\Sdk\Request\Payload\Parts\GenericPart;

class Profile extends AbstractPayload
{
    protected AbstractEndpoint $endpoint;

    protected int $profileId;
    protected string $serverKey;
    protected string $clientKey;

    /**
     * @throws InvalidConfigurationException when credentials are invalid
     */
    public function __construct(AbstractEndpoint $endpoint, int $profileId, string $serverKey)
    {
        if ($profileId <= 0) {
            throw InvalidConfigurationException::missing('profile_id');
        }

        $serverKey = trim($serverKey);
        if ('' === $serverKey) {
            throw InvalidConfigurationException::missing('server_key');
        }

        $this->endpoint = $endpoint;
        $this->profileId = $profileId;
        $this->serverKey = $serverKey;

        $this->buildHeader(new GenericPart(
            [
                'Authorization: ' . $this->serverKey,
            ]
        ));

        $this->buildBody(new GenericPart(
            [
                'profile_id' => $this->profileId,
            ]
        ));
    }

    public function getProfileId(): int
    {
        return $this->profileId;
    }

    public function getServerKey(): string
    {
        return $this->serverKey;
    }

    /**
     * Non-reversible hint identifying which server key is in use, safe to put
     * in a log or an exception message.
     */
    public function getServerKeyPrefix(): string
    {
        return Redactor::keyHint($this->serverKey);
    }

    public function getUrl(): string
    {
        return $this->endpoint->getUrl();
    }
}
