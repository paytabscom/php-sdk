<?php

namespace Paytabs\Sdk\Response\Payload\Payloads\Payment;

use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Paytabs as PaytabsSDK;
use Paytabs\Sdk\Response\Payload\Parts\ParentRequest;
use Paytabs\Sdk\Response\Payload\Parts\PaymentInfo;
use Paytabs\Sdk\Response\Payload\Parts\PaymentResult;
use Paytabs\Sdk\Response\Payload\Parts\ThreeDSDetails;
use Paytabs\Sdk\Response\Payload\Payloads\Payment;

class Completed extends Payment
{
    public string $ipn_trace;

    public string $previous_tran_ref;
    public string $tran_currency;

    public string $tran_class;

    public PaymentResult $payment_result;
    public PaymentInfo $payment_info;

    public ParentRequest $parentRequest;

    public ThreeDSDetails $threeDSDetails;

    public string $token;

    public int $invoice_id;

    public string $return;

    protected TranClass $tranClass;

    public function setTranClass(string $tran_class)
    {
        $this->tran_class = $tran_class;
        $this->tranClass = TranClass::tryFrom(strtolower($tran_class)) ?? TranClass::UnKnown;

        if (TranClass::UnKnown === $this->tranClass) {
            PaytabsSDK::getLogger()->error('Unknown transaction class', [
                'tran_class' => $tran_class,
            ]);
        }
    }
}
