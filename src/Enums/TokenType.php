<?php

namespace Paytabs\Sdk\Enums;

class TokenType extends EnumString
{
    public const Registered = 'registered';
    public const Unscheduled = 'unscheduled';
    public const RecurringFixed = 'recurring_fixed';
    public const RecurringVariable = 'recurring_variable';

    public static function Registered()
    {
        return new self(self::Registered);
    }

    public static function Unscheduled()
    {
        return new self(self::Unscheduled);
    }

    public static function RecurringFixed()
    {
        return new self(self::RecurringFixed);
    }

    public static function RecurringVariable()
    {
        return new self(self::RecurringVariable);
    }
}
