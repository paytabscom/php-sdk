<?php

namespace Paytabs\Sdk\Enums;

class TokenType extends EnumString
{
    const Registered = 'registered';
    const Unscheduled = 'unscheduled';
    const RecurringFixed = 'recurring_fixed';
    const RecurringVariable = 'recurring_variable';

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
