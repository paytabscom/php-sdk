<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Logger;

/**
 * PSR-3 logger that writes through PHP's error_log().
 *
 * This is the default for diagnostics and as fallback.
 */
final class ErrorLogLogger extends AbstractLogger
{
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        error_log(rtrim($this->buildMessage((string) $level, $message, $context), PHP_EOL));
    }
}
