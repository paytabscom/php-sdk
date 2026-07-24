<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Profile;

use Paytabs\Sdk\Exceptions\InvalidConfigurationException;
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
        if (!is_numeric($profileId) || (int) $profileId <= 0) {
            throw InvalidConfigurationException::missing('profile_id');
        }

        if ('' === $serverKey) {
            throw InvalidConfigurationException::missing('server_key');
        }

        $this->endpoint = $endpoint;
        $this->profileId = $profileId;
        $this->serverKey = $serverKey;

        $this->buildHeader(new GenericPart(
            [
                'Authorization: '.$this->serverKey,
            ]
        ));

        $this->buildBody(new GenericPart(
            [
                'profile_id' => $this->profileId,
            ]
        ));
    }

    public function getServerKey(): string
    {
        return $this->serverKey;
    }

    public function getServerKeyPrefix(): string
    {
        return substr($this->serverKey, 0, 10);
    }

    public function getUrl(): string
    {
        return $this->endpoint->getUrl();
    }
}
