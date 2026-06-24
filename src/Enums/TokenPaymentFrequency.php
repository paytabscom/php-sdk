<?php

namespace Paytabs\Sdk\Enums;

enum TokenPaymentFrequency: string
{
    case Ad_Hoc = 'AD_HOC';
    case Daily = 'DAILY';
    case Fortnightly = 'FORTNIGHTLY';
    case Monthly = 'MONTHLY';
    case Quarterly = 'QUARTERLY';
    case Twice_Yearly = 'TWICE_YEARLY';
    case Weekly = 'WEEKLY';
    case Yearly = 'YEARLY';
    case Other = 'OTHER';
}
