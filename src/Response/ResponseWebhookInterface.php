<?php

namespace Paytabs\Sdk\Response;

interface ResponseWebhookInterface extends ResponseInterface
{
    public function isGenuine(): bool;
}
