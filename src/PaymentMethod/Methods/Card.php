<?php

namespace Paytabs\Sdk\PaymentMethod\Methods;

use Paytabs\Sdk\PaymentMethod\AbstractMethod;

final class Card extends AbstractMethod
{
    public const ID = 2;

    public const CODE = 'card';
    public const PT_CODE = 'paytabs_'.self::CODE;

    public const CODE_ALIASES = [
        'creditcard',
    ];

    public const TITLE = 'PayTabs - Card Payments';

    public const ACTIVE = true;

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
