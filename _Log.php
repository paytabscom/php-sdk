<?php


use Logger\LoggerInterface;

class Log implements LoggerInterface
{

    private static $instances = [];

    public static function getInstance(): Log
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }

        return self::$instances[$cls];
    }

    //

    private function log($msg, int $severity = 1, array $meta = null)
    {
        switch ($severity) {
            case 1:
                echo 'Notice: ';
                break;
            case 2:
                echo 'Debug: ';
                break;
            case 3:
                echo 'Warning: ';
                break;
            case 4:
                echo 'Error: ';
                break;
            case 5:
                echo 'Critical: ';
                break;

            default:
                echo 'Log: ';
                break;
        }

        if (is_array($msg)) {
            var_export($msg);
        } else {
            echo 'Log: ' . $msg . PHP_EOL;
        }
    }

    public function debug(string $message, ?array $meta)
    {
        $this->log($message, 2, $meta);
    }
    public function warning(string $message, ?array $meta)
    {
        $this->log($message, 3, $meta);
    }
    public function error(string $message, ?array $meta)
    {
        $this->log($message, 4, $meta);
    }
    public function critical(string $message, ?array $meta)
    {
        $this->log($message, 5, $meta);
    }
}
