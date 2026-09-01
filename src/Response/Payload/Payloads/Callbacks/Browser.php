<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Payload\Payloads\Callbacks;

use Paytabs\Sdk\Enums\TranStatus;
use Paytabs\Sdk\Exceptions\UnknownResponseValueException;
use Paytabs\Sdk\Response\Payload\Payloads\Paytabs;

class Browser extends Paytabs
{
    public ?string $acquirerMessage = null;
    public ?string $acquirerRRN = null;

    public ?string $tranRef = null;
    public ?string $cartId = null;

    public ?string $customerEmail = null;

    public ?string $respStatus = null;
    public ?TranStatus $tranStatus = null;

    public ?string $respMessage = null;
    public ?string $respCode = null;

    public ?string $signature = null;

    public ?string $token = null;

    public function setRespStatus(string $respStatus): void
    {
        $this->respStatus = $respStatus;
        $this->tranStatus = TranStatus::tryFrom(strtoupper($respStatus)) ?? TranStatus::Unknown;

        if (TranStatus::Unknown === $this->tranStatus) {
            static::logger()->error('Unknown transaction status', [
                'tran_status' => $respStatus,
            ]);

            if (self::isStrictMode()) {
                throw UnknownResponseValueException::forTranStatus($respStatus);
            }

        }
    }

    /**
     * Three-state: true, false, or null when the callback carried no respStatus.
     *
     * Beware: `!isTransactionSuccessful()` is true for both a decline AND an
     * unknown status, and a pending transaction is not successful either. Test
     * for `true` explicitly before treating a transaction as paid.
     */
    public function isTransactionSuccessful(): ?bool
    {
        return $this->tranStatus?->isSuccessful();
    }

    /**
     * Null when the callback carried no respStatus.
     */
    public function isTransactionFailed(): ?bool
    {
        return $this->tranStatus?->isFailed();
    }

    /**
     * True while the gateway has not reached a final decision, i.e. neither
     * isTransactionSuccessful() nor isTransactionFailed().
     */
    public function isTransactionPending(): ?bool
    {
        return $this->tranStatus?->isNotFinal();
    }
}
