<?php

namespace Paytabs\Sdk\Enums;

class HttpRequestPart extends EnumInt
{
    const Header = 1;
    const Body = 2;
    const Query = 3;
    const Path = 4;

    public static function Header()
    {
        return new self(self::Header);
    }

    public static function Body()
    {
        return new self(self::Body);
    }

    public static function Query()
    {
        return new self(self::Query);
    }

    public static function Path()
    {
        return new self(self::Path);
    }
}
