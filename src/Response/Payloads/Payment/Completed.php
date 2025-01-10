<?php

namespace Response\Payloads\Payment;

use Enums\TranClass;
use Response\Parts\ParentRequest;
use Response\Parts\PaymentInfo;
use Response\Parts\PaymentResult;
use Response\Payloads\Payment;

class Completed extends Payment
{
    public string $ipn_trace;

    public string $previous_tran_ref;
    public string $tran_currency;

    public string $tran_class;
    protected TranClass $tranClass;

    public PaymentResult $payment_result;
    public PaymentInfo $payment_info;

    public ParentRequest $parentRequest;

    public string $token;

    //

    public function setTranClass(string $tran_class)
    {
        $this->tran_class = $tran_class;
        $this->tranClass = TranClass::tryFrom(strtolower($tran_class));
    }
}
