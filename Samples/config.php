<?php

declare(strict_types=1);

use Paytabs\Sdk\Profile\EndpointsFactory;

function getConfig(string $key, $default = null): mixed
{
    static $env = null;

    if (null === $env) {
        $envPath = __DIR__.'/.env';
        $env = parse_ini_file($envPath);

        if (false === $env) {
            throw new RuntimeException(sprintf('Failed to load samples environment file: %s', $envPath));
        }

        if (!array_key_exists('ENDPOINT_CODE', $env)) {
            throw new InvalidArgumentException('Config key "ENDPOINT_CODE" not found');
        }

        $env['ENDPOINT'] = EndpointsFactory::getEndpointByCode($env['ENDPOINT_CODE']);
    }

    if (!array_key_exists($key, $env)) {
        if (null !== $default) {
            return $default;
        }

        throw new InvalidArgumentException(sprintf('Config key "%s" not found', $key));
    }

    return $env[$key];
}
