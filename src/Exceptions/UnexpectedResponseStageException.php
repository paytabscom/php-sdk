<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Exceptions;

/**
 * A stage accessor was called for a stage the response is not in — e.g.
 * getFailure() without checking isFailure() first.
 *
 * Extends LogicException because it signals a caller-sequencing bug to fix in
 * development, not a runtime condition to handle.
 */
final class UnexpectedResponseStageException extends \LogicException implements PaytabsExceptionInterface
{
    public static function forStage(string $expected): self
    {
        return new self(\sprintf(
            'Response is not a %1$s. Check is%1$s() before calling get%1$s().',
            ucfirst($expected)
        ));
    }

    public static function missingPayload(): self
    {
        return new self('No response data has been set on this payload.');
    }
}
