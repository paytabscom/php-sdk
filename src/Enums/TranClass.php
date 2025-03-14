<?php

namespace Paytabs\Sdk\Enums;

class TranClass extends EnumString
{
    public const Ecom = 'ecom';
    public const Moto = 'moto';
    public const Recurring = 'recurring';
    public const EcomToken = 'ecomtoken';
    public const CAuth = 'c/auth';
    public const NFC = 'nfc';

    public const UnKnown = 'unknown';

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

    public static function EcomToken()
    {
        return new self(self::EcomToken);
    }

    public static function CAuth()
    {
        return new self(self::CAuth);
    }

    public static function NFC()
    {
        return new self(self::NFC);
    }

    public static function UnKnown()
    {
        return new self(self::UnKnown);
    }
}
