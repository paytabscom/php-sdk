<?php

namespace Paytabs\Sdk\Enums;

class FramedTarget extends EnumInt
{
    public const NoReturn = 1;
    public const ReturnParent = 2;
    public const ReturnTop = 3;

    public static function NoReturn()
    {
        return new self(self::NoReturn);
    }

    public static function ReturnParent()
    {
        return new self(self::ReturnParent);
    }

    public static function ReturnTop()
    {
        return new self(self::ReturnTop);
    }
}
