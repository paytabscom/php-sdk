<?php

namespace Paytabs\Sdk;

use Paytabs\Sdk\Logger\BrowserLog;
use Paytabs\Sdk\Logger\Log;
use Psr\Log\LoggerInterface;

abstract class Paytabs
{
    // Version
    public const VERSION = '3.0.0';

    // LOG
    protected const LOG_DAILY = true;

    protected const LOG_PATH = '/var/log/paytabs-sdk/';
    protected const LOG_FILE_NAME = 'debug_paytabs';
    protected const LOG_FILE_EXTENSION = '.log';

    protected const LOG_PREFIX = 'PayTabs';

    final public static function getVersion(): string
    {
        return self::VERSION;
    }

    public static function getLogger(): LoggerInterface
    {
        $useBrowser = filter_var((string) getenv('PAYTABS_LOG_BROWSER'), FILTER_VALIDATE_BOOL);

        if ($useBrowser) {
            return BrowserLog::getInstance(static::getLogFile(), static::LOG_PREFIX);
        }

        return Log::getInstance(static::getLogFile(), static::LOG_PREFIX);
    }

    public static function getLogFile(): string
    {
        $basePath = (string) getenv('PAYTABS_LOG_PATH');
        if ('' === trim($basePath)) {
            $basePath = static::LOG_PATH;
        }

        if ('' === trim($basePath)) {
            $basePath = rtrim(sys_get_temp_dir(), \DIRECTORY_SEPARATOR);
        }

        $basePath = rtrim($basePath, \DIRECTORY_SEPARATOR) . \DIRECTORY_SEPARATOR;
        if (!is_dir($basePath)) {
            @mkdir($basePath, 0775, true);
        }

        $logFile = static::LOG_FILE_NAME;

        if (static::LOG_DAILY) {
            $time = date('Y-m-d');
            $logFile .= '-' . $time;
        }

        return $basePath . $logFile . static::LOG_FILE_EXTENSION;
    }
}
