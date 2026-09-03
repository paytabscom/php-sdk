<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Logger;

use Psr\Log\AbstractLogger as PsrAbstractLogger;
use Psr\Log\LogLevel;

abstract class AbstractLogger extends PsrAbstractLogger
{
    private string $logPrefix = '';

    public function __construct(string $logPrefix = '')
    {
        $this->logPrefix = $logPrefix;
    }

    protected function isImportant(string $level): bool
    {
        $important = [
            LogLevel::ALERT,
            LogLevel::ERROR,
            LogLevel::CRITICAL,
            LogLevel::EMERGENCY,
        ];

        return \in_array($level, $important, true);
    }

    protected function buildMessage(string $level, string|\Stringable $message, array $context): string
    {
        $_prefix
            = date('c')
            . ' '
            . $this->logPrefix
            . '.'
            . $level
            . ': ';

        $_safeContext = Redactor::context($context);

        // Interpolate from the redacted context so a `{pan}` placeholder cannot
        // reintroduce cardholder data into the message.
        $_userMessage = Redactor::message($this->interpolate($message, $_safeContext));

        return $_prefix
            . $_userMessage
            . ' '
            . $this->encodeContext($_safeContext)
            . PHP_EOL;
    }

    /**
     * Never returns `false`: on invalid UTF-8 a bare json_encode() would drop
     * the entire context silently.
     */
    protected function encodeContext(array $context): string
    {
        $encoded = json_encode(
            $context,
            JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE | JSON_PARTIAL_OUTPUT_ON_ERROR
        );

        if (false === $encoded) {
            return '{"_log_error":"context could not be encoded"}';
        }

        return $encoded;
    }

    /**
     * Interpolates context values into the message placeholders.
     * Sample:
     * A message with brace-delimited placeholder names
     * $message = "User {username} created";
     * A context array of placeholder names => replacement values
     * $context = array('username' => 'bolivar');.
     *
     * @param mixed $message
     */
    private function interpolate($message, array $context = []): string
    {
        // build a replacement array with braces around the context keys
        $replace = [];
        foreach ($context as $key => $val) {
            // check that the value can be cast to string
            if (!\is_array($val) && (!\is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }
}
