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
}
