<?php

namespace Paytabs\Sdk\Profile;

use Paytabs\Sdk\Request\Payload\Parts\GenericPart;
use Paytabs\Sdk\Request\Payload\AbstractPayload;

class Profile extends AbstractPayload
{
    protected Endpoint $endpoint;

    protected int $profileId;
    protected string $serverKey;
    protected string $clientKey;

    public function __construct(Endpoint $endpoint, int $profileId, string $serverKey)
    {
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

    public function getUrl(): string
    {
        return $this->endpoint->getUrl();
    }
}
