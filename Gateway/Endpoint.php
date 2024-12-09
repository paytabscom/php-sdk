<?php

namespace Gateway;

abstract class Endpoint
{
    protected const CODE = '';
    protected const TITLE = '';
    protected const URL = '';

    //

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
