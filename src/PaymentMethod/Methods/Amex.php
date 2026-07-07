<?php

declare(strict_types=1);

namespace Paytabs\Sdk\PaymentMethod\Methods;

use Paytabs\Sdk\PaymentMethod\AbstractMethod;

final class Amex extends AbstractMethod
{
    public const ID = 5;

    public const CODE = 'amex';
    public const PT_CODE = 'paytabs_'.self::CODE;

    public const CODE_ALIASES = [
        'creditcard',
    ];

    public const TITLE = 'PayTabs - American Express';

    public const ACTIVE = true;

    final public const CURRENCIES = [
        'AED', 'SAR', 'USD', 'AZN', 'TRY',
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
