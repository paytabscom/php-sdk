<?php

namespace Paytabs\Sdk\Gateway;

use Holder\Payload\AbstractPayload;

class Gateway extends AbstractPayload
{
    protected Endpoint $endpoint;

    protected int $profileId;
    protected string $serverKey;
    protected string $clientKey;

    //

    public function __construct(Endpoint $endpoint, int $profileId, string $serverKey)
    {
        $this->endpoint = $endpoint;
        $this->profileId = $profileId;
        $this->serverKey = $serverKey;

        //

        $this->buildHeader(
            [
                'Authorization: ' . $this->serverKey,
            ]
        );

        $this->buildBody(
            [
                'profile_id' => $this->profileId,
            ]
        );
    }

    //

    public function getUrl(): string
    {
        return $this->endpoint->getUrl();
    }
}
