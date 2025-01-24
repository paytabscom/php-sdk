<?php

namespace Paytabs\Sdk\Logger;

use Exception;
use Psr\Log\AbstractLogger;

class Log extends AbstractLogger
{
    private string $logFile = '';
    private string $logPrefix = '';

    private static $instances = [];

    //

    private function __construct(string $logFile, string $logPrefix)
    {
        $this->logFile = $logFile;
        $this->logPrefix = $logPrefix;
    }

    public static function getInstance(string $logFile, string $logPrefix = ''): self
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static($logFile, $logPrefix);
        }

        return self::$instances[$cls];
    }

    //

    public function log($level, string $message, array $context = []): void
    {
        $logMessage = $this->buildMessage($level, $message, $context);

        if (file_put_contents($this->logFile, $logMessage, FILE_APPEND) === false) {
            throw new Exception('Can not write to the Log');
        }
    }

    private function buildMessage($level, string $message, array $context): string
    {
        $_prefix =
            date('c')
            . ' '
            . $this->logPrefix
            . '.'
            . $level
            . ': ';

        $_userMessage = $this->interpolate($message, $context);

        $_context = json_encode($context);

        $logMessage =
            $_prefix
            . $_userMessage
            . ' '
            . $_context
            . PHP_EOL;

        return $logMessage;
    }

    /**
     * Interpolates context values into the message placeholders.
     * Sample:
     * A message with brace-delimited placeholder names
     * $message = "User {username} created";
     * A context array of placeholder names => replacement values
     * $context = array('username' => 'bolivar');
     */
    private function interpolate($message, array $context = array()): string
    {
        // build a replacement array with braces around the context keys
        $replace = array();
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
