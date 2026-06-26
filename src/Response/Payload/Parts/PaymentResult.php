<?php

namespace Paytabs\Sdk\Response\Payload\Parts;

use Paytabs\Sdk\Enums\TranStatus;

class PaymentResult
{
    // "payment_result"

    public string $response_status; // "A",
    public TranStatus $tranStatus;

    public string $response_code; // "G08490",
    public string $response_message; // "Authorised",
    public string $acquirer_ref; // "TRAN0001.6764066A.00033E57",
    public string $cvv_result; // " ",
    public string $avs_result; // " ",
    public string $transaction_time; // "2024-12-19T11:41:30Z"

    public function setResponseStatus(string $response_status): void
    {
        $this->response_status = $response_status;
        $this->tranStatus = TranStatus::tryFrom(strtoupper($response_status)) ?? TranStatus::Unknown;
    }
}
