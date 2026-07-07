<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Payload\Payloads;

class Redirect extends Payment
{
    public string $callback;
    public string $return;

    public string $redirect_url;

    public int $serviceId;
}
