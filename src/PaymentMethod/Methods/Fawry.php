<?php

namespace Paytabs\Sdk\PaymentMethod\Methods;

use Paytabs\Sdk\PaymentMethod\AbstractMethod;

final class Fawry extends AbstractMethod
{
    const ID = 51;

    const CODE = 'fawry';
    const PT_CODE = 'paytabs_' . self::CODE;

    const TITLE = 'PayTabs - Fawry';

    const ACTIVE = false;

    //

    final protected const SUPPORT_ANY_CURRENCY = false;
    final const CURRENCIES = [
        'EGP'
    ];

    final protected const IS_CARD = false;
    final protected const SUPPORT_CARD_FEATURES = false;

    // Fawry, Sadad
    final protected const IS_ASYNC = true;
    final protected const SUPPORT_ASYNC = true;

    final protected const SUPPORT_TOKENIZATION = false;

    final protected const SUPPORT_AUTH_CAPTURE = false;
    final protected const SUPPORT_MULTIPLE_CAPTURE = false;

    final protected const SUPPORT_REFUND = true;
    final protected const SUPPORT_REFUND_PARTIAL = false;
    final protected const SUPPORT_MULTIPLE_REFUND = false;

    final protected const SUPPORT_FRAMED = true;
}
