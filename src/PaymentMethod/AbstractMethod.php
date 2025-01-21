<?php

namespace Paytabs\Sdk\PaymentMethod;

abstract class AbstractMethod
{
    const ID = 0;

    const CODE = '';
    const PT_CODE = 'paytabs_' . self::CODE;

    const CODE_ALIASES = [];

    const TITLE = '';

    const ACTIVE = true;

    //

    protected const SUPPORT_ANY_CURRENCY = false;
    protected const CURRENCIES = [];

    protected const IS_CARD = false;
    protected const SUPPORT_CARD_FEATURES = false;

    // Fawry, Sadad
    protected const IS_ASYNC = false;
    protected const SUPPORT_ASYNC = false;

    protected const SUPPORT_TOKENIZATION = false;

    protected const SUPPORT_AUTH_CAPTURE = false;
    protected const SUPPORT_MULTIPLE_CAPTURE = false;

    protected const SUPPORT_REFUND = false;
    protected const SUPPORT_REFUND_PARTIAL = false;
    protected const SUPPORT_MULTIPLE_REFUND = false;

    protected const SUPPORT_FRAMED = false;

    //

    final public function matchesCode(string $code): bool
    {
        $code = strtolower($code);
        if (static::CODE === $code) {
            return true;
        }
        if (\in_array($code, static::CODE_ALIASES, true)) {
            return true;
        }

        return false;
    }

    //

    final public static function isCard(): bool
    {
        return static::IS_CARD;
    }

    final public static function supportCard(): bool
    {
        return static::SUPPORT_CARD_FEATURES;
    }

    final public static function isDeferred(): bool
    {
        return static::IS_ASYNC;
    }

    final public static function supportDeferred(): bool
    {
        return static::SUPPORT_ASYNC;
    }

    final public static function supportCurrency(string $currency): bool
    {
        return
            static::SUPPORT_ANY_CURRENCY
            || \in_array(strtoupper($currency), static::CURRENCIES, true);
    }

    final public static function supportedCurrencies(): array
    {
        return static::CURRENCIES;
    }

    final public static function supportFramed(): bool
    {
        return static::SUPPORT_FRAMED;
    }

    final public static function supportTokenization(): bool
    {
        return static::SUPPORT_TOKENIZATION;
    }

    final public static function supportRefund(): bool
    {
        return static::SUPPORT_REFUND;
    }

    final public static function supportRefundPartial(): bool
    {
        return
            static::supportRefund()
            && static::SUPPORT_REFUND_PARTIAL;
    }

    final public static function supportMultipleRefund(): bool
    {
        return
            static::supportRefund()
            && static::SUPPORT_MULTIPLE_REFUND;
    }

    final public static function supportAuthCapture(): bool
    {
        return static::SUPPORT_AUTH_CAPTURE;
    }

    final public static function supportMultipleCapture(): bool
    {
        return
            static::supportAuthCapture()
            && static::SUPPORT_MULTIPLE_CAPTURE;
    }
}
