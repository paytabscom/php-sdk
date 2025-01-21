<?php

namespace Paytabs\Sdk\PaymentMethod\Methods;

use Paytabs\Sdk\PaymentMethod\AbstractMethod;

final class ApplePay extends AbstractMethod
{
    const ID = 10;

    const CODE = 'applepay';
    const PT_CODE = 'paytabs_' . self::CODE;

    const CODE_ALIASES = [
        'apple_pay'
    ];

    const TITLE = 'PayTabs - ApplePay';

    const ACTIVE = true;

    //

    final protected const SUPPORT_ANY_CURRENCY = true;

    final protected const IS_CARD = false;
    final protected const SUPPORT_CARD_FEATURES = false;

    final protected const IS_ASYNC = false;
    final protected const SUPPORT_ASYNC = false;

    final protected const SUPPORT_TOKENIZATION = true;

    final protected const SUPPORT_AUTH_CAPTURE = true;
    final protected const SUPPORT_MULTIPLE_CAPTURE = true;

    final protected const SUPPORT_REFUND = true;
    final protected const SUPPORT_REFUND_PARTIAL = true;
    final protected const SUPPORT_MULTIPLE_REFUND = true;

    final protected const SUPPORT_FRAMED = false;
}
