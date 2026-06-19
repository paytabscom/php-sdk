<?php

namespace Paytabs\Sdk\Logger;

class BrowserLog extends Log
{
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $styles = '';
        if ($this->isImportant($level)) {
            $styles = 'color: red;';
        }

        echo "<pre style='{$styles}'>{$level}: {$message}" . PHP_EOL;
        print_r($context);
        echo '</pre>';
    }
}
