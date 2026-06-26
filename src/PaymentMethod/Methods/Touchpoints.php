<?php

namespace Paytabs\Sdk\PaymentMethod\Methods;

use Paytabs\Sdk\PaymentMethod\AbstractMethod;

final class Touchpoints extends AbstractMethod
{
    public const ID = 202;

    public const CODE = 'touchpoints';
    public const PT_CODE = 'paytabs_'.self::CODE;

    public const CODE_ALIASES = [
        'adcbtouchpoints',
    ];

    public const TITLE = 'PayTabs - Touchpoints';

    public const ACTIVE = true;

    final public const CURRENCIES = [
        'AED',
    ];

    final protected const SUPPORT_ANY_CURRENCY = false;

    final protected const IS_CARD = true;
    final protected const SUPPORT_CARD_FEATURES = true;

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
