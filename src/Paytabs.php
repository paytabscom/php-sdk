<?php

namespace Paytabs\Sdk;

use Paytabs\Sdk\Logger\Log;
use Psr\Log\LoggerInterface;

abstract class Paytabs
{
    const VERSION = '3.0.0';

    final public static function getVersion(): string
    {
        return Paytabs::VERSION;
    }

    public static ?Log $logger = null;

    public static function Logger(): LoggerInterface
    {
        if (Paytabs::$logger == null) {
            Paytabs::$logger = new Log;
        }

        return Paytabs::$logger;
    }
}
