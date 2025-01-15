<?php

namespace Paytabs\Sdk\PaymentMethod\Methods;

use Paytabs\Sdk\PaymentMethod\AbstractMethod;

class Fawry extends AbstractMethod
{
    const ID = 51;

    const CODE = 'fawry';
    const PT_CODE = 'paytabs_' . self::CODE;

    const TITLE = 'PayTabs - Fawry';

    const ACTIVE = false;

    //

    protected static bool $supportAnyCurrency = false;
    protected static array $currencies = [
        'EGP'
    ];

    protected static bool $isCard = false;
    protected static bool $supportCard = false;

    // Fawry, Sadad
    protected static bool $isAsync = true;
    protected static bool $supportAsync = true;

    protected static bool $supportTokenization = false;

    protected static bool $supportAuthCapture = false;
    protected static bool $supportMultipleCapture = false;

    protected static bool $supportRefund = true;
    protected static bool $supportRefundPartial = false;
    protected static bool $supportMultipleRefund = false;

    protected static bool $supportFramed = true;
}
