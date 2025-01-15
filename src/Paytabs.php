<?php

namespace Paytabs\Sdk;

use Paytabs\Sdk\Logger\Log;
use Psr\Log\LoggerInterface;

abstract class Paytabs
{
    public const VERSION = '3.0.0';

    final public static function getVersion(): string
    {
        return self::VERSION;
    }

    public static ?Log $logger = null;

    public static function Logger(): LoggerInterface
    {
        if (self::$logger === null) {
            self::$logger = new Log();
        }

        return self::$logger;
    }
}
