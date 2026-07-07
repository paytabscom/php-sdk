<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Logger;

class BrowserLog extends AbstractLogger
{
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $styles = '';
        if ($this->isImportant($level)) {
            $styles = 'color: red;';
        }

        echo "<pre style='{$styles}'>{$level}: {$message}".PHP_EOL;
        print_r($context);
        echo '</pre>';
    }
}
