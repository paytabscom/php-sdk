<?php

namespace Paytabs\Sdk\Enums;

class CardDiscountType extends EnumInt
{
    public const Fixed = 1;
    public const Percent = 2;

    public static function Fixed()
    {
        return new self(self::Fixed);
    }

    public static function Percent()
    {
        return new self(self::Percent);
    }
}
