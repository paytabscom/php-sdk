<?php

namespace Paytabs\Sdk\PaymentMethod\Methods;

use Paytabs\Sdk\PaymentMethod\AbstractMethod;

class All extends AbstractMethod
{
    const ID = 1;

    const CODE = 'all';
    const PT_CODE = 'paytabs_' . self::CODE;

    const TITLE = 'PayTabs';

    const ACTIVE = true;

    //

    protected static bool $supportAnyCurrency = true;

    protected static bool $isCard = false;
    protected static bool $supportCard = true;

    // Fawry, Sadad
    protected static bool $isAsync = false;
    protected static bool $supportAsync = true;

    protected static bool $supportTokenization = true;

    protected static bool $supportAuthCapture = true;
    protected static bool $supportMultipleCapture = true;

    protected static bool $supportRefund = true;
    protected static bool $supportRefundPartial = true;
    protected static bool $supportMultipleRefund = true;

    protected static bool $supportFramed = true;
}
