<?php

namespace Paytabs\Sdk\PaymentMethod\Methods;

use Paytabs\Sdk\PaymentMethod\AbstractMethod;

final class ApplePay extends AbstractMethod
{
    public const ID = 10;

    public const CODE = 'applepay';
    public const PT_CODE = 'paytabs_'.self::CODE;

    public const CODE_ALIASES = [
        'apple_pay',
    ];

    public const TITLE = 'PayTabs - ApplePay';

    public const ACTIVE = true;

    protected const SUPPORT_ANY_CURRENCY = true;

    protected const IS_CARD = false;
    protected const SUPPORT_CARD_FEATURES = false;

    protected const IS_ASYNC = false;
    protected const SUPPORT_ASYNC = false;

    protected const SUPPORT_TOKENIZATION = true;

    protected const SUPPORT_AUTH_CAPTURE = true;
    protected const SUPPORT_MULTIPLE_CAPTURE = true;

    protected const SUPPORT_REFUND = true;
    protected const SUPPORT_REFUND_PARTIAL = true;
    protected const SUPPORT_MULTIPLE_REFUND = true;

    protected const SUPPORT_FRAMED = false;
}
