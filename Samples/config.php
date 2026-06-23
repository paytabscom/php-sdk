<?php

declare(strict_types=1);

use Paytabs\Sdk\Profile\EndpointsFactory;

function getConfig(string $key, $default = null): mixed
{
    $env = parse_ini_file('.env');
    $env['ENDPOINT'] = EndpointsFactory::getEndpointByCode($env['ENDPOINT_CODE']);

    if (!array_key_exists($key, $env)) {
        if ($default !== null) {
            return $default;
        }
        throw new \InvalidArgumentException(sprintf('Config key "%s" not found', $key));
    }

    return $env[$key];
}
