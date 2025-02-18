<?php

namespace Paytabs\Sdk\PaymentMethod\Methods;

use Paytabs\Sdk\PaymentMethod\AbstractMethod;

final class Fawry extends AbstractMethod
{
    public const ID = 51;

    public const CODE = 'fawry';
    public const PT_CODE = 'paytabs_'.self::CODE;

    public const TITLE = 'PayTabs - Fawry';

    public const ACTIVE = false;

    public const CURRENCIES = [
        'EGP',
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

    protected const SUPPORT_REFUND = true;
    protected const SUPPORT_REFUND_PARTIAL = false;
    protected const SUPPORT_MULTIPLE_REFUND = false;

    protected const SUPPORT_FRAMED = true;
}
