<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Payload\Payloads\Payment;

use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranStatus;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Exceptions\UnknownResponseValueException;
use Paytabs\Sdk\Response\Payload\Parts\ParentRequest;
use Paytabs\Sdk\Response\Payload\Parts\PaymentInfo;
use Paytabs\Sdk\Response\Payload\Parts\PaymentResult;
use Paytabs\Sdk\Response\Payload\Parts\ThreeDSDetails;
use Paytabs\Sdk\Response\Payload\Payloads\Payment;

class Completed extends Payment
{
    // Only returned in the Webhook callback (IPN) flow, not in the Query API flow.
    public ?string $ipn_trace = null;

    public ?string $previous_tran_ref = null;
    public ?string $tran_currency = null;

    /**
     * The type originally requested, when the gateway performed a different one:
     * a `Sale` downgraded to `Auth` on hold-on-reject, or a `Void` carried out as
     * a `Release`. Only returned in the Query API flow, not in the Webhook (IPN)
     * flow, so a callback alone cannot tell you what was asked for.
     */
    public ?string $original_tran_type = null;
    public ?TranType $originalTranType = null;

    // Only returned in the Webhook callback (IPN) flow, not in the Query API flow.
    public ?string $tran_class = null;

    public ?PaymentResult $payment_result = null;
    public ?PaymentInfo $payment_info = null;

    public ?ParentRequest $parentRequest = null;

    // Only returned in the Webhook callback (IPN) flow, not in the Query API flow.
    public ?ThreeDSDetails $threeDSDetails = null;

    public ?string $token = null;

    // Only returned in the Webhook callback (IPN) flow, not in the Query API flow.
    public ?int $invoice_id = null;

    public ?string $return = null;

    // Only returned in the Query API flow, not in the Webhook callback (IPN) flow.
    public ?int $serviceId = null;

    public ?TranClass $tranClass = null;

    public function setTranClass(string $tran_class): void
    {
        $this->tran_class = $tran_class;
        $this->tranClass = TranClass::tryFrom(strtolower($tran_class)) ?? TranClass::Unknown;

        if (TranClass::Unknown === $this->tranClass) {
            static::logger()->error('Unknown transaction class', [
                'tran_class' => $tran_class,
            ]);

            if (self::isStrictMode()) {
                throw UnknownResponseValueException::forTranClass($tran_class);
            }
        }
    }

    public function setOriginalTranType(string $original_tran_type): void
    {
        $this->original_tran_type = $original_tran_type;
        $this->originalTranType = TranType::tryFrom(strtolower($original_tran_type)) ?? TranType::Unknown;

        if (TranType::Unknown === $this->originalTranType) {
            static::logger()->error('Unknown original transaction type', [
                'original_tran_type' => $original_tran_type,
            ]);

            if (self::isStrictMode()) {
                throw UnknownResponseValueException::forTranType($original_tran_type);
            }
        }
    }

    /**
     * Whether the gateway performed a different transaction type than requested.
     *
     * Two known cases: a `Sale` downgraded to `Auth` (hold on reject — the funds
     * are held, not taken), and a `Void` carried out as a `Release`. Returns
     * null when the gateway did not report an original type, which includes
     * every webhook payload.
     */
    public function isTranTypeChanged(): ?bool
    {
        if (null === $this->originalTranType || null === $this->tranType) {
            return null;
        }

        return $this->originalTranType !== $this->tranType;
    }

    /**
     * Whether the gateway authorised this transaction.
     *
     * Reports the transaction, not the order: for a follow-up (refund, void,
     * release, capture) `true` means the follow-up succeeded, not that the cart
     * was paid. Check isFollowup() before treating this as "paid".
     *
     * Beware: this and isTransactionFailed() are BOTH false for a pending or
     * on-hold transaction (`P` / `H`). Do not treat `!isTransactionFailed()` as
     * "paid" — use isTransactionPending() to tell the third state apart.
     */
    public function isTransactionSuccessful(): ?bool
    {
        return $this->payment_result?->isSuccessful();
    }

    public function isTransactionFailed(): ?bool
    {
        return $this->payment_result?->isFailed();
    }

    /**
     * True while the gateway has not reached a final decision, i.e. neither
     * isTransactionSuccessful() nor isTransactionFailed().
     */
    public function isTransactionPending(): ?bool
    {
        return $this->payment_result?->isNotFinal();
    }

    /**
     * Whether this transaction is a follow-up (refund, void, release, capture,
     * auth extension) rather than a payment.
     *
     * `null` when the gateway reported no transaction type.
     *
     * Necessary but not sufficient: a capture or a tokenised repeat charge
     * reports `tran_type: Sale`, so this returns false for it. Use
     * isAgainstEarlierTransaction() to catch those.
     */
    public function isFollowup(): ?bool
    {
        return $this->tranType?->isFollowup();
    }

    /**
     * Whether this transaction was performed against an earlier one.
     *
     * `previous_tran_ref` is the dependable marker; `tran_type` is not, because
     * a capture, a tokenised repeat charge, and the sale that settles a pending
     * offline payment all report `Sale`.
     */
    public function isAgainstEarlierTransaction(): bool
    {
        return null !== $this->previous_tran_ref && '' !== $this->previous_tran_ref;
    }

    /**
     * Whether a deferred payment has reached a final outcome.
     *
     * A pending (`P`) sale — SADAD, Aman, Fawry — is not resolved by a status
     * change on the original transaction. It is resolved by a second IPN with a
     * new `tran_ref`, which is either the sale that settles it (reported as
     * `Sale` with `previous_tran_ref` set) or an expiry.
     *
     * Ported from v2's `TranIsPaymentComplete()`.
     */
    public function isDeferredPaymentResolved(): bool
    {
        if (TranType::Sale === $this->tranType && $this->isAgainstEarlierTransaction()) {
            return true;
        }

        return TranStatus::Expired === $this->payment_result?->tranStatus;
    }
}
