<?php

namespace Paytabs\Sdk\Enums;

class HttpType extends EnumInt
{
    const GET = 1;
    const POST = 2;

    public static function GET()
    {
        return new self(self::GET);
    }

    public static function POST()
    {
        return new self(self::POST);
    }
}
