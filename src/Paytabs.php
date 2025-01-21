<?php

namespace Paytabs\Sdk;

use Paytabs\Sdk\Logger\Log;
use Psr\Log\LoggerInterface;

abstract class Paytabs
{
    // Version
    public const VERSION = '3.0.0';

    // LOG
    protected const LOG_DAILY = true;

    protected const LOG_PATH = '/var/www/html/logs/';
    protected const LOG_FILE_NAME = 'debug_paytabs';
    protected const LOG_FILE_EXTENSION = '.log';

    protected const LOG_PREFIX = 'PayTabs';

    //

    final public static function getVersion(): string
    {
        return self::VERSION;
    }

    public static function getLogger(): LoggerInterface
    {
        return Log::getInstance(static::getLogFile(), static::LOG_PREFIX);
    }

    public static function getLogFile(): string
    {
        $logFile = static::LOG_FILE_NAME;

        if (static::LOG_DAILY) {
            $time = date('Y-m-d');
            $logFile .= '-' . $time;
        }

        return static::LOG_PATH . $logFile . static::LOG_FILE_EXTENSION;
    }
}
