<?php

namespace Paytabs\Sdk\Exceptions;

final class EndpointNotFoundException extends \InvalidArgumentException
{
    public static function forCode(string $code): self
    {
        return new self("Endpoint not found for Code: {$code}");
    }
}
