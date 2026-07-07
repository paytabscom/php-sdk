<?php

declare(strict_types=1);

namespace Paytabs\Sdk\PaymentMethod\Methods;

use Paytabs\Sdk\PaymentMethod\AbstractMethod;

final class Mada extends AbstractMethod
{
    public const ID = 101;

    public const CODE = 'mada';
    public const PT_CODE = 'paytabs_'.self::CODE;

    public const TITLE = 'PayTabs - mada';

    public const ACTIVE = true;

    final public const CURRENCIES = [
        'SAR',
    ];

    final protected const SUPPORT_ANY_CURRENCY = false;

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
