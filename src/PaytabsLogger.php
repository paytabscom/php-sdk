<?php

declare(strict_types=1);

namespace Paytabs\Sdk;

use Paytabs\Sdk\Logger\BrowserLog;
use Paytabs\Sdk\Logger\FileLog;
use Psr\Log\LoggerInterface;

class PaytabsLogger
{
    // LOG
    protected const LOG_DAILY = true;

    protected const LOG_PATH = '/var/log/paytabs-sdk/';
    protected const LOG_FILE_NAME = 'debug_paytabs';
    protected const LOG_FILE_EXTENSION = '.log';

    protected const LOG_PREFIX = 'PayTabs';

    public LoggerInterface $logger;

    private function __construct(
        ?LoggerInterface $logger = null,
        bool $browserLog = false
    ) {
        $this->logger = $logger ?? static::createLogger($browserLog);
    }

    public static function getInstance(
        ?LoggerInterface $logger = null,
        bool $browserLog = false
    ): self {
        return new static($logger, $browserLog);
    }

    public static function getLogFile(?string $logPath = null): string
    {
        $basePath = '';
        if ('' === trim($logPath ?? '')) {
            $basePath = static::LOG_PATH;
        }

        if ('' === trim($basePath)) {
            $basePath = rtrim(sys_get_temp_dir(), \DIRECTORY_SEPARATOR);
        }

        $basePath = rtrim($basePath, \DIRECTORY_SEPARATOR).\DIRECTORY_SEPARATOR;
        if (!is_dir($basePath)) {
            try {
                mkdir($basePath, 0o775, true);
            } catch (\Throwable $e) {
                error_log('Failed to create log directory: '.$basePath.' - '.$e->getMessage());
                // throw new \RuntimeException('Failed to create log directory: ' . $basePath, 0, $e);
            }
        }

        $logFile = static::LOG_FILE_NAME;

        if (static::LOG_DAILY) {
            $time = date('Y-m-d');
            $logFile .= '-'.$time;
        }

        return $basePath.$logFile.static::LOG_FILE_EXTENSION;
    }

    private static function createLogger(bool $browserLog): LoggerInterface
    {
        if ($browserLog) {
            return new BrowserLog(static::LOG_PREFIX);
        }

        return new FileLog(static::LOG_PREFIX, static::getLogFile());
    }
}
