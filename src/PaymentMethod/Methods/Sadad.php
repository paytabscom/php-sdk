<?php

declare(strict_types=1);

namespace Paytabs\Sdk\PaymentMethod\Methods;

use Paytabs\Sdk\PaymentMethod\AbstractMethod;

final class Sadad extends AbstractMethod
{
    public const ID = 120;

    public const CODE = 'sadad';
    public const PT_CODE = 'paytabs_'.self::CODE;

    public const TITLE = 'PayTabs - Sadad';

    public const ACTIVE = true;

    final public const CURRENCIES = [
        'SAR',
    ];

    final protected const SUPPORT_ANY_CURRENCY = false;

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
