<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response;

interface ResponseWebhookInterface extends ResponseInterface
{
    public function isGenuine(): bool;
}
