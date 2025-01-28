<?php

namespace Paytabs\Sdk\Enums;

class TranStatus extends EnumString
{
    const Authorised = 'A';
    const OnHold = 'H';
    const Pending = 'P';
    const Voided = 'V';
    const Error = 'E';
    const Declined = 'D';
    const Expired = 'X';

    public static function Authorised()
    {
        return new self(self::Authorised);
    }

    public static function OnHold()
    {
        return new self(self::OnHold);
    }

    public static function Pending()
    {
        return new self(self::Pending);
    }

    public static function Voided()
    {
        return new self(self::Voided);
    }

    public static function Error()
    {
        return new self(self::Error);
    }

    public static function Declined()
    {
        return new self(self::Declined);
    }

    public static function Expired()
    {
        return new self(self::Expired);
    }

    //

    public function isSuccessful(): bool
    {
        return $this === TranStatus::Authorised;
    }

    public function isNotFinal(): bool
    {
        return $this->isPending() || $this->isOnHold();
    }

    public function isFailed(): bool
    {
        return !($this->isSuccessful() || $this->isNotFinal());
    }

    public function isOnHold(): bool
    {
        return $this === TranStatus::OnHold;
    }

    public function isPending(): bool
    {
        return $this === TranStatus::Pending;
    }

    public function isExpired(): bool
    {
        return $this === TranStatus::Expired;
    }
}
