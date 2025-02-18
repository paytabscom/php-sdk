<?php

namespace Paytabs\Sdk\Logger;

class BrowserLog extends Log
{
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        echo "<pre>{$level}: {$message}".PHP_EOL;
        print_r($context);
        echo '</pre>';
    }
}
