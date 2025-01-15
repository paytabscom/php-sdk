<?php

namespace Paytabs\Sdk\PaymentMethod;

abstract class AbstractMethod
{
    const ID = 0;

    const CODE = '';
    const PT_CODE = 'paytabs_' . self::CODE;

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

    public function isCard(): bool
    {
        return self::$isCard;
    }

    public function supportCard(): bool
    {
        return self::$supportCard;
    }

    public function isDeferred(): bool
    {
        return self::$isAsync;
    }

    public function supportDeferred(): bool
    {
        return self::$supportAsync;
    }

    public function supportCurrency(string $currency): bool
    {
        return
            self::$supportAnyCurrency
            || in_array(strtoupper($currency), self::$currencies);
    }

    public function supportedCurrencies(): array
    {
        return self::$currencies;
    }

    public function supportFramed(): bool
    {
        return self::$supportFramed;
    }

    public function supportTokenization(): bool
    {
        return self::$supportTokenization;
    }

    public function supportRefund(): bool
    {
        return self::$supportRefund;
    }

    public function supportRefundPartial(): bool
    {
        return
            self::supportRefund()
            && self::$supportRefundPartial;
    }

    public function supportMultipleRefund(): bool
    {
        return
            self::supportRefund()
            && self::$supportMultipleRefund;
    }

    public function supportAuthCapture(): bool
    {
        return self::$supportAuthCapture;
    }

    public function supportMultipleCapture(): bool
    {
        return
            self::supportAuthCapture()
            && self::$supportMultipleCapture;
    }
}
