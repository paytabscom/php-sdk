<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Exceptions;

final class EndpointNotFoundException extends \InvalidArgumentException implements PaytabsExceptionInterface
{
    public static function forCode(string $code): self
    {
        return new self("Endpoint not found for Code: {$code}");
    }
}
