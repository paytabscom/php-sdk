<?php

namespace Paytabs\Sdk\PaymentMethod\Methods;

use Paytabs\Sdk\PaymentMethod\AbstractMethod;

final class KNet extends AbstractMethod
{
    public const ID = 700;

    public const CODE = 'knet';
    public const PT_CODE = 'paytabs_'.self::CODE;

    public const TITLE = 'PayTabs - KNet';

    public const ACTIVE = true;

    final public const CURRENCIES = [
        'KWD', 'USD',
    ];

    final protected const SUPPORT_ANY_CURRENCY = false;

    final protected const IS_CARD = true;
    final protected const SUPPORT_CARD_FEATURES = true;

    final protected const IS_ASYNC = false;
    final protected const SUPPORT_ASYNC = false;

    final protected const SUPPORT_TOKENIZATION = false;

    final protected const SUPPORT_AUTH_CAPTURE = false;
    final protected const SUPPORT_MULTIPLE_CAPTURE = false;

    final protected const SUPPORT_REFUND = true;
    final protected const SUPPORT_REFUND_PARTIAL = true;
    final protected const SUPPORT_MULTIPLE_REFUND = true;

    final protected const SUPPORT_FRAMED = false;
}
