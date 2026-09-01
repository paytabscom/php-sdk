<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Exceptions;

/**
 * A field the caller asked for is absent from the gateway response.
 */
final class MissingResponseFieldException extends \RuntimeException implements PaytabsExceptionInterface
{
    public static function forField(string $field): self
    {
        return new self(\sprintf('Response field "%s" is missing or null.', $field));
    }
}
