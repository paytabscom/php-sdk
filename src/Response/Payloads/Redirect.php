<?php

namespace Response\Payloads;

class Redirect extends Payment
{
    public string $callback;
    public string $return;

    public string $redirect_url;

    public int $serviceId;
}
