<?php

declare(strict_types=1);

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
    case Canceled = 'C';

    case Unknown = 'unknown';

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
        return !($this->isSuccessful() || $this->isNotFinal() || $this->isUnknown());
    }

    /**
     * Hold on reject: authorized, but deliberately not captured because risk
     * screening flagged it. A `sale` in this state has effectively become an
     * `auth` — only the merchant can capture or void it, on their own liability.
     * It will never settle on its own, so route it to review rather than a poller.
     */
    public function isOnHold(): bool
    {
        return TranStatus::OnHold === $this;
    }

    /**
     * On a sale, the customer pays through another medium (SADAD, Aman, Fawry)
     * and settlement takes hours to days, ending as Expired if they never pay.
     * On a refund, this is a normal accepted-and-processing state.
     */
    public function isPending(): bool
    {
        return TranStatus::Pending === $this;
    }

    public function isExpired(): bool
    {
        return TranStatus::Expired === $this;
    }

    public function isUnknown(): bool
    {
        return TranStatus::Unknown === $this;
    }
}
