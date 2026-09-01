<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Payload\Parts;

use Paytabs\Sdk\Enums\TranStatus;

class PaymentResult
{
    // "payment_result"

    public ?string $response_status = null; // "A",
    public ?TranStatus $tranStatus = null;

    public ?string $response_code = null; // "G08490",
    public ?string $response_message = null; // "Authorised",

    public ?string $transaction_time = null; // "2024-12-19T11:41:30Z"

    // Only returned in the Webhook callback (IPN) flow, not in the Query API flow.
    public ?string $acquirer_ref = null; // "TRAN0001.6764066A.00033E57",
    public ?string $cvv_result = null; // " ",
    public ?string $avs_result = null; // " ",

    public function setResponseStatus(string $response_status): void
    {
        $this->response_status = $response_status;
        $this->tranStatus = TranStatus::tryFrom(strtoupper($response_status)) ?? TranStatus::Unknown;
    }

    public function isSuccessful(): ?bool
    {
        // No response_status means nothing to be successful about. Absent is not
        // success, so this is the safe default.
        return $this->tranStatus?->isSuccessful();
    }

    public function isFailed(): ?bool
    {
        return $this->tranStatus?->isFailed();
    }

    /**
     * On-hold or pending: the gateway has not reached a final decision, so this
     * is neither isSuccessful() nor isFailed().
     */
    public function isNotFinal(): ?bool
    {
        return $this->tranStatus?->isNotFinal();
    }

    public function __toString(): string
    {
        return sprintf(
            '%s (%s) - %s',
            $this->response_status ?? '-',
            $this->tranStatus?->name ?? '-',
            $this->response_message ?? '-'
        );
    }
}
