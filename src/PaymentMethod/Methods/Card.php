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

    protected const SUPPORT_ANY_CURRENCY = true;

    protected const IS_CARD = true;
    protected const SUPPORT_CARD_FEATURES = true;

    protected const IS_ASYNC = false;
    protected const SUPPORT_ASYNC = false;

    protected const SUPPORT_TOKENIZATION = true;

    protected const SUPPORT_AUTH_CAPTURE = true;
    protected const SUPPORT_MULTIPLE_CAPTURE = true;

    protected const SUPPORT_REFUND = true;
    protected const SUPPORT_REFUND_PARTIAL = true;
    protected const SUPPORT_MULTIPLE_REFUND = true;

    protected const SUPPORT_FRAMED = true;
}
