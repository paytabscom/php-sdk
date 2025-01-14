<?php

namespace Paytabs\Sdk\Response\Responses;

use Paytabs\Sdk\Response\Payloads\Callbacks\Ipn;

class Callback extends AbstractResponse
{
    protected array $postArray;

    //

    public function getResponse(): Ipn
    {
        $payload = new Ipn();
        $payload->fromJson($this->getJson());

        return $payload;
    }

    //

    final public function isValid(): bool
    {
        // Lower case all keys
        $headers = array_change_key_case($this->headers);

        $signature = @$headers['signature'] ?? '';

        $serverKey = $this->gateway->getServerKey();

        return $this->isGenuine($this->getRaw(), $signature, $serverKey);
    }
}
