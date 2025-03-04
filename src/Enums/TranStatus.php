<?php

namespace Paytabs\Sdk\Enums;

class TranStatus extends EnumString
{
    public const Authorised = 'A';
    public const OnHold = 'H';
    public const Pending = 'P';
    public const Voided = 'V';
    public const Error = 'E';
    public const Declined = 'D';
    public const Expired = 'X';
    public const Canceled = 'C';

    public const UnKnown = 'unknown';

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

    public static function Canceled()
    {
        return new self(self::Canceled);
    }

    public function isSuccessful(): bool
    {
        return TranStatus::Authorised === $this;
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
        return TranStatus::OnHold === $this;
    }

    public function isPending(): bool
    {
        return TranStatus::Pending === $this;
    }

    public function isExpired(): bool
    {
        return TranStatus::Expired === $this;
    }

    public static function UnKnown()
    {
        return new self(self::UnKnown);
    }
}
