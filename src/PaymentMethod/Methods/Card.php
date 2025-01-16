<?php

namespace Paytabs\Sdk\PaymentMethod\Methods;

use Paytabs\Sdk\PaymentMethod\AbstractMethod;

class Card extends AbstractMethod
{
    const ID = 2;

    const CODE = 'card';

    const CODE_ALIASES = [
        'creditcard',
    ];

    const TITLE = 'PayTabs - Card Payments';

    const ACTIVE = true;

    //

    protected static bool $supportAnyCurrency = true;

    protected static bool $isCard = true;
    protected static bool $supportCard = true;

    // Fawry, Sadad
    protected static bool $isAsync = false;
    protected static bool $supportAsync = false;

    protected static bool $supportTokenization = true;

    protected static bool $supportAuthCapture = true;
    protected static bool $supportMultipleCapture = true;

    protected static bool $supportRefund = true;
    protected static bool $supportRefundPartial = true;
    protected static bool $supportMultipleRefund = true;

    protected static bool $supportFramed = true;
}
