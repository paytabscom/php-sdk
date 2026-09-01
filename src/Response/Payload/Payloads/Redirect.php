<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Payload\Payloads;

class Redirect extends Payment
{
    public ?string $callback = null;
    public ?string $return = null;

    public ?string $redirect_url = null;

    public ?int $serviceId = null;
}
