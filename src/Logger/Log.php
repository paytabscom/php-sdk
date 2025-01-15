<?php

namespace Paytabs\Sdk\Logger;

use Psr\Log\AbstractLogger;

class Log extends AbstractLogger
{
    private static $instances = [];

    public static function getInstance(): self
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }

        return self::$instances[$cls];
    }

    //

    public function log($level, string|\Stringable $message, array $context = []): void
    {
        echo '<pre> ' . $level . ': ' . $message . PHP_EOL;
        print_r($context);
        echo '</pre>';
    }
}
