<?php

namespace Logger;

interface LoggerInterface
{
    public function debug(string $message, ?array $meta);
    public function warning(string $message, ?array $meta);
    public function error(string $message, ?array $meta);
    public function critical(string $message, ?array $meta);
}
