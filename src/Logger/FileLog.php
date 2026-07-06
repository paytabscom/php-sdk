<?php

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

        if (false === file_put_contents($this->logFile, $logMessage, FILE_APPEND)) {
            error_log('Failed to write to log file: '.$this->logFile);

            throw new \Exception('Can not write to the Log');
        }
    }
}
