<?php

namespace Paytabs\Sdk\Enums;

class ResponseStage extends EnumInt
{
    public const Redirect = 1;
    public const Error = 2;
    public const Completed = 3;

    public const UnKnown = 10;

    public static function Redirect()
    {
        return new self(self::Redirect);
    }

    public static function Error()
    {
        return new self(self::Error);
    }

    public static function Completed()
    {
        return new self(self::Completed);
    }

    public static function UnKnown()
    {
        return new self(self::UnKnown);
    }
}
