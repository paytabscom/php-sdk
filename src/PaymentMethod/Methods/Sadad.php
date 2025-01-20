<?php

namespace Paytabs\Sdk\PaymentMethod\Methods;

use Paytabs\Sdk\PaymentMethod\AbstractMethod;

final class Sadad extends AbstractMethod
{
    const ID = 50;

    const CODE = 'sadad';
    const PT_CODE = 'paytabs_' . self::CODE;

    const TITLE = 'PayTabs - Sadad';

    const ACTIVE = true;

    //

    final protected const SUPPORT_ANY_CURRENCY = false;
    final const CURRENCIES = [
        'SAR'
    ];

    final protected const IS_CARD = false;
    final protected const SUPPORT_CARD_FEATURES = false;

    // Fawry, Sadad
    final protected const IS_ASYNC = true;
    final protected const SUPPORT_ASYNC = true;

    final protected const SUPPORT_TOKENIZATION = false;

    final protected const SUPPORT_AUTH_CAPTURE = false;
    final protected const SUPPORT_MULTIPLE_CAPTURE = false;

    final protected const SUPPORT_REFUND = false;
    final protected const SUPPORT_REFUND_PARTIAL = false;
    final protected const SUPPORT_MULTIPLE_REFUND = false;

    final protected const SUPPORT_FRAMED = true;
}
