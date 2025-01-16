<?php

namespace Paytabs\Sdk\PaymentMethod\Methods;

use Paytabs\Sdk\PaymentMethod\AbstractMethod;

class Sadad extends AbstractMethod
{
    const ID = 50;

    const CODE = 'sadad';

    const TITLE = 'PayTabs - Sadad';

    const ACTIVE = true;

    //

    protected static bool $supportAnyCurrency = false;
    protected static array $currencies = [
        'SAR'
    ];

    protected static bool $isCard = false;
    protected static bool $supportCard = false;

    // Fawry, Sadad
    protected static bool $isAsync = true;
    protected static bool $supportAsync = true;

    protected static bool $supportTokenization = false;

    protected static bool $supportAuthCapture = false;
    protected static bool $supportMultipleCapture = false;

    protected static bool $supportRefund = false;
    protected static bool $supportRefundPartial = false;
    protected static bool $supportMultipleRefund = false;

    protected static bool $supportFramed = true;
}
