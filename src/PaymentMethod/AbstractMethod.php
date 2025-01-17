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

    protected static bool $supportAnyCurrency;
    protected static array $currencies = [];

    protected static bool $isCard;
    protected static bool $supportCard;

    // Fawry, Sadad
    protected static bool $isAsync;
    protected static bool $supportAsync;

    protected static bool $supportTokenization;

    protected static bool $supportAuthCapture;
    protected static bool $supportMultipleCapture;

    protected static bool $supportRefund;
    protected static bool $supportRefundPartial;
    protected static bool $supportMultipleRefund;

    protected static bool $supportFramed;

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

    final public function isCard(): bool
    {
        return self::$isCard;
    }

    final public function supportCard(): bool
    {
        return self::$supportCard;
    }

    final public function isDeferred(): bool
    {
        return self::$isAsync;
    }

    final public function supportDeferred(): bool
    {
        return self::$supportAsync;
    }

    final public function supportCurrency(string $currency): bool
    {
        return
            self::$supportAnyCurrency
            || \in_array(strtoupper($currency), self::$currencies, true);
    }

    final public function supportedCurrencies(): array
    {
        return self::$currencies;
    }

    final public function supportFramed(): bool
    {
        return self::$supportFramed;
    }

    final public function supportTokenization(): bool
    {
        return self::$supportTokenization;
    }

    final public function supportRefund(): bool
    {
        return self::$supportRefund;
    }

    final public function supportRefundPartial(): bool
    {
        return
            self::supportRefund()
            && self::$supportRefundPartial;
    }

    final public function supportMultipleRefund(): bool
    {
        return
            self::supportRefund()
            && self::$supportMultipleRefund;
    }

    final public function supportAuthCapture(): bool
    {
        return self::$supportAuthCapture;
    }

    final public function supportMultipleCapture(): bool
    {
        return
            self::supportAuthCapture()
            && self::$supportMultipleCapture;
    }
}
