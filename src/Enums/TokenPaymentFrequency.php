<?php

namespace Paytabs\Sdk\Enums;

class TokenPaymentFrequency extends EnumString
{
    public const Ad_Hoc = 'AD_HOC';
    public const Daily = 'DAILY';
    public const Fortnightly = 'FORTNIGHTLY';
    public const Monthly = 'MONTHLY';
    public const Quarterly = 'QUARTERLY';
    public const Twice_Yearly = 'TWICE_YEARLY';
    public const Weekly = 'WEEKLY';
    public const Yearly = 'YEARLY';
    public const Other = 'OTHER';

    public static function Ad_Hoc()
    {
        return new self(self::Ad_Hoc);
    }

    public static function Daily()
    {
        return new self(self::Daily);
    }

    public static function Fortnightly()
    {
        return new self(self::Fortnightly);
    }

    public static function Monthly()
    {
        return new self(self::Monthly);
    }

    public static function Quarterly()
    {
        return new self(self::Quarterly);
    }

    public static function Twice_Yearly()
    {
        return new self(self::Twice_Yearly);
    }

    public static function Weekly()
    {
        return new self(self::Weekly);
    }

    public static function Yearly()
    {
        return new self(self::Yearly);
    }

    public static function Other()
    {
        return new self(self::Other);
    }
}
