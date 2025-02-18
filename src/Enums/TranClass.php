<?php

namespace Paytabs\Sdk\Enums;

class TranClass extends EnumString
{
    public const Ecom = 'ecom';
    public const Moto = 'moto';
    public const Recurring = 'recurring';

    public static function Ecom()
    {
        return new self(self::Ecom);
    }

    public static function Moto()
    {
        return new self(self::Moto);
    }

    public static function Recurring()
    {
        return new self(self::Recurring);
    }
}
