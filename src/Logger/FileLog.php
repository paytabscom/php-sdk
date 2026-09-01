<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Logger;

class FileLog extends AbstractLogger
{
    private string $logFile = '';

    public function __construct(string $logPrefix, string $logFile)
    {
        parent::__construct($logPrefix);
        $this->logFile = $logFile;
    }

    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $logMessage = $this->buildMessage($level, $message, $context);

        $isNewFile = !file_exists($this->logFile);

        // LOCK_EX: concurrent IPN and browser-return hits would otherwise
        // interleave and corrupt entries.
        $written = @file_put_contents($this->logFile, $logMessage, FILE_APPEND | LOCK_EX);

        if (false === $written) {
            // A logger must never break the payment flow it is observing.
            // An un-writable log directory is an operations problem, not a reason
            // to fail a transaction, so this reports and returns.
            error_log('PayTabs SDK: failed to write to log file: ' . $this->logFile);

            return;
        }

        // Protect the log: it holds gateway payloads.
        if ($isNewFile) {
            @chmod($this->logFile, 0o600);
        }
    }
}
