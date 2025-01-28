<?php

namespace Paytabs\Sdk\Enums;

use Exception;

class FramedTarget extends EnumInt
{
    const NoReturn = 1;
    const ReturnParent = 2;
    const ReturnTop = 3;

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
