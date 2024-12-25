<?php

namespace Response\Parts;

use Enums\TranStatus;

class PaymentResult
{
    /*
    "payment_result"
    */

    public string $response_status; // "A",
    public TranStatus $tranStatus;

    public string $response_code; // "G08490",
    public string $response_message; // "Authorised",
    public string $acquirer_ref; // "TRAN0001.6764066A.00033E57",
    public string $cvv_result; // " ",
    public string $avs_result; // " ",
    public string $transaction_time; // "2024-12-19T11:41:30Z"
}
