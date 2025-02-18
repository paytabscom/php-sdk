<?php

namespace Paytabs\Sdk\Enums;

enum TranStatus: string
{
    case Authorised = 'A';
    case OnHold = 'H';
    case Pending = 'P';
    case Voided = 'V';
    case Error = 'E';
    case Declined = 'D';
    case Expired = 'X';

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
}
