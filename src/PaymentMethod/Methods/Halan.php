<?php

namespace Paytabs\Sdk\PaymentMethod\Methods;

use Paytabs\Sdk\PaymentMethod\AbstractMethod;

final class Halan extends AbstractMethod
{
    public const ID = 2;

    public const CODE = 'halan';
    public const PT_CODE = 'paytabs_'.self::CODE;

    public const TITLE = 'PayTabs - Halan';

    public const ACTIVE = true;

    final public const CURRENCIES = [
        'EGP',
    ];

    final protected const SUPPORT_ANY_CURRENCY = false;

    final protected const IS_CARD = false;
    final protected const SUPPORT_CARD_FEATURES = false;

    final protected const IS_ASYNC = false;
    final protected const SUPPORT_ASYNC = false;

    final protected const SUPPORT_TOKENIZATION = false;

    final protected const SUPPORT_AUTH_CAPTURE = false;
    final protected const SUPPORT_MULTIPLE_CAPTURE = false;

    final protected const SUPPORT_REFUND = false;
    final protected const SUPPORT_REFUND_PARTIAL = false;
    final protected const SUPPORT_MULTIPLE_REFUND = false;

    final protected const SUPPORT_FRAMED = true;
}
