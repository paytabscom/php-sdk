<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Logger;

/**
 * Debug logger that writes into the HTTP response.
 *
 * Everything is HTML-escaped and redacted.
 */
class BrowserLog extends AbstractLogger
{
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $class = $this->isImportant((string) $level) ? 'paytabs-log paytabs-log--important' : 'paytabs-log';

        $style = $this->isImportant((string) $level)
            ? 'color: red; white-space: pre-wrap;'
            : 'white-space: pre-wrap;';

        // buildMessage() applies redaction, newline stripping and the timestamp
        // prefix, keeping this consistent with FileLog.
        $line = $this->buildMessage((string) $level, $message, $context);

        printf(
            '<pre class="%s" style="%s">%s</pre>',
            $this->escape($class),
            $this->escape($style),
            $this->escape($line)
        );
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
