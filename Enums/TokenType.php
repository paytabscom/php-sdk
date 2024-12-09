<?php

namespace Enums;

enum TokenType: string
{
    case Registered = 'registered';
    case Unscheduled = 'unscheduled';
    case RecurringFixed = 'recurring_fixed';
    case RecurringVariable = 'recurring_variable';
}
