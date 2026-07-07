<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response;

abstract class AbstractResponseWebhook extends AbstractResponse implements ResponseWebhookInterface
{
    abstract public function isGenuine(): bool;
}
