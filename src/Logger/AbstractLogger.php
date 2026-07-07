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
            .' '
            .$this->logPrefix
            .'.'
            .$level
            .': ';

        $_userMessage = $this->interpolate($message, $context);

        $_context = json_encode($context);

        return $_prefix
            .$_userMessage
            .' '
            .$_context
            .PHP_EOL;
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
                $replace['{'.$key.'}'] = $val;
            }
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }
}
