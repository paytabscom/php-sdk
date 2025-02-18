<?php

namespace Paytabs\Sdk\Gateway;

abstract class Endpoint
{
    protected const CODE = '';
    protected const TITLE = '';
    protected const URL = '';

    private static $instances = [];

    public static function getInstance(): self
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }

        return self::$instances[$cls];
    }

    public function getCode(): string
    {
        return static::CODE;
    }

    public function getTitle(): string
    {
        return static::TITLE;
    }

    public function getUrl(): string
    {
        return static::URL;
    }
}
