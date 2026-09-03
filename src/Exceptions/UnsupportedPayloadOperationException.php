<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Exceptions;

/**
 * A payload builder was asked for something its transaction type forbids —
 * payment methods on an Own Form, shipping details on a recurring payment.
 *
 * Extends BadMethodCallException because it signals a caller bug to fix in
 * development, not a runtime condition to handle. It is deliberately distinct
 * from a declined payment, which is a normal response.
 */
final class UnsupportedPayloadOperationException extends \BadMethodCallException implements PaytabsExceptionInterface
{
    public static function forPayload(string $operation, string $payload): self
    {
        return new self(\sprintf('%s is not supported by %s.', $operation, $payload));
    }

    public static function unsupportedValue(string $what, string $payload): self
    {
        return new self(\sprintf('Invalid %s for %s.', $what, $payload));
    }
}
