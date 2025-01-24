<?php

namespace Paytabs\Sdk\PaymentMethod\Methods;

use Paytabs\Sdk\PaymentMethod\AbstractMethod;

final class All extends AbstractMethod
{
    const ID = 1;

    const CODE = 'all';
    const PT_CODE = 'paytabs_' . self::CODE;

    const TITLE = 'PayTabs';

    const ACTIVE = true;

    //

    protected const SUPPORT_ANY_CURRENCY = true;

    protected const IS_CARD = false;
    protected const SUPPORT_CARD_FEATURES = true;

    protected const IS_ASYNC = false;
    protected const SUPPORT_ASYNC = true;

    protected const SUPPORT_TOKENIZATION = true;

    protected const SUPPORT_AUTH_CAPTURE = true;
    protected const SUPPORT_MULTIPLE_CAPTURE = true;

    protected const SUPPORT_REFUND = true;
    protected const SUPPORT_REFUND_PARTIAL = true;
    protected const SUPPORT_MULTIPLE_REFUND = true;

    protected const SUPPORT_FRAMED = true;
}
