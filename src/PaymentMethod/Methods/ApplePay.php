<?php

namespace Paytabs\Sdk\PaymentMethod\Methods;

use Paytabs\Sdk\PaymentMethod\AbstractMethod;

class ApplePay extends AbstractMethod
{
    const ID = 10;

    const CODE = 'applepay';
    const PT_CODE = 'paytabs_' . self::CODE;

    const CODE_ALIASES = [
        'apple_pay'
    ];

    const TITLE = 'PayTabs - ApplePay';

    const ACTIVE = true;

    //

    protected static bool $supportAnyCurrency = true;

    protected static bool $isCard = false;
    protected static bool $supportCard = false;

    // Fawry, Sadad
    protected static bool $isAsync = false;
    protected static bool $supportAsync = false;

    protected static bool $supportTokenization = true;

    protected static bool $supportAuthCapture = true;
    protected static bool $supportMultipleCapture = true;

    protected static bool $supportRefund = true;
    protected static bool $supportRefundPartial = true;
    protected static bool $supportMultipleRefund = true;

    protected static bool $supportFramed = false;
}
