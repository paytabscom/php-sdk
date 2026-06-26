<?php

namespace Paytabs\Sdk\PaymentMethod\Methods;

use Paytabs\Sdk\PaymentMethod\AbstractMethod;

final class Tamara extends AbstractMethod
{
    public const ID = 2;

    public const CODE = 'tamara';
    public const PT_CODE = 'paytabs_'.self::CODE;

    public const TITLE = 'PayTabs - Tamara';

    public const ACTIVE = true;

    final public const CURRENCIES = [
        'AED', 'SAR',
    ];

    final protected const SUPPORT_ANY_CURRENCY = false;

    final protected const IS_CARD = false;
    final protected const SUPPORT_CARD_FEATURES = false;

    final protected const IS_ASYNC = false;
    final protected const SUPPORT_ASYNC = false;

    final protected const SUPPORT_TOKENIZATION = false;

    final protected const SUPPORT_AUTH_CAPTURE = false;
    final protected const SUPPORT_MULTIPLE_CAPTURE = false;

    final protected const SUPPORT_REFUND = true;
    final protected const SUPPORT_REFUND_PARTIAL = true;
    final protected const SUPPORT_MULTIPLE_REFUND = true;

    final protected const SUPPORT_FRAMED = true;
}
