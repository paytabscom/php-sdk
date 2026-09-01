<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Payload\Payloads\Payment;

use Paytabs\Sdk\Enums\TranClass;
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
     */
    public function isFollowup(): ?bool
    {
        return $this->tranType?->isFollowup();
    }
}
