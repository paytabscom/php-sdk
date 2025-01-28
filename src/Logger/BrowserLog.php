<?php

namespace Paytabs\Sdk\Logger;

class BrowserLog extends Log
{

    public function log($level, $message, array $context = array())
    {
        echo "<pre>$level: $message" . PHP_EOL;
        print_r($context);
        echo '</pre>';
    }
}
