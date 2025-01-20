<?php

namespace Paytabs\Sdk\PaymentMethod\Methods;

use Paytabs\Sdk\PaymentMethod\AbstractMethod;

final class Card extends AbstractMethod
{
    const ID = 2;

    const CODE = 'card';
    const PT_CODE = 'paytabs_' . self::CODE;

    const CODE_ALIASES = [
        'creditcard',
    ];

    const TITLE = 'PayTabs - Card Payments';

    const ACTIVE = true;

    //

    final protected const SUPPORT_ANY_CURRENCY = true;

    final protected const IS_CARD = true;
    final protected const SUPPORT_CARD_FEATURES = true;

    final protected const IS_ASYNC = false;
    final protected const SUPPORT_ASYNC = false;

    final protected const SUPPORT_TOKENIZATION = true;

    final protected const SUPPORT_AUTH_CAPTURE = true;
    final protected const SUPPORT_MULTIPLE_CAPTURE = true;

    final protected const SUPPORT_REFUND = true;
    final protected const SUPPORT_REFUND_PARTIAL = true;
    final protected const SUPPORT_MULTIPLE_REFUND = true;

    final protected const SUPPORT_FRAMED = true;
}
