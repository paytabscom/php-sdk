<?php

namespace Paytabs\Sdk\PaymentMethod\Methods;

use Paytabs\Sdk\PaymentMethod\AbstractMethod;

final class StcPay extends AbstractMethod
{
    public const ID = 100;

    public const CODE = 'stcpay';
    public const PT_CODE = 'paytabs_'.self::CODE;

    public const TITLE = 'PayTabs - STC Pay';

    public const ACTIVE = true;

    final public const CURRENCIES = [
        'SAR',
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
