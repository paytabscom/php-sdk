<?php

declare(strict_types=1);

namespace Paytabs\Sdk\PaymentMethod\Methods;

use Paytabs\Sdk\PaymentMethod\AbstractMethod;

final class KNetCredit extends AbstractMethod
{
    public const ID = 702;

    public const CODE = 'knetcredit';
    public const PT_CODE = 'paytabs_'.self::CODE;

    public const TITLE = 'PayTabs - KNet (Credit)';

    public const ACTIVE = true;

    final public const CURRENCIES = [
        'KWD',
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

    final protected const SUPPORT_FRAMED = false;
}
