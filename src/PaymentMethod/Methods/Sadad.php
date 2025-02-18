<?php

namespace Paytabs\Sdk\PaymentMethod\Methods;

use Paytabs\Sdk\PaymentMethod\AbstractMethod;

final class Sadad extends AbstractMethod
{
    public const ID = 50;

    public const CODE = 'sadad';
    public const PT_CODE = 'paytabs_'.self::CODE;

    public const TITLE = 'PayTabs - Sadad';

    public const ACTIVE = true;

    public const CURRENCIES = [
        'SAR',
    ];

    protected const SUPPORT_ANY_CURRENCY = false;

    protected const IS_CARD = false;
    protected const SUPPORT_CARD_FEATURES = false;

    // Fawry, Sadad
    protected const IS_ASYNC = true;
    protected const SUPPORT_ASYNC = true;

    protected const SUPPORT_TOKENIZATION = false;

    protected const SUPPORT_AUTH_CAPTURE = false;
    protected const SUPPORT_MULTIPLE_CAPTURE = false;

    protected const SUPPORT_REFUND = false;
    protected const SUPPORT_REFUND_PARTIAL = false;
    protected const SUPPORT_MULTIPLE_REFUND = false;

    protected const SUPPORT_FRAMED = true;
}
