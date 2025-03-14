<?php

namespace Paytabs\Sdk\Response\Payload\Payloads\Callbacks;

use Paytabs\Sdk\Enums\TranStatus;
use Paytabs\Sdk\Paytabs as PaytabsSDK;
use Paytabs\Sdk\Response\Payload\Payloads\Paytabs;

class Browser extends Paytabs
{
    public string $acquirerMessage;
    public string $acquirerRRN;

    public string $tranRef;
    public string $cartId;

    public string $customerEmail;

    public string $respStatus;
    public TranStatus $tranStatus;

    public string $respMessage;
    public string $respCode;

    public string $signature;

    public string $token;

    public function setRespStatus(string $respStatus)
    {
        $this->respStatus = $respStatus;
        $this->tranStatus = TranStatus::tryFrom(strtoupper($respStatus)) ?? TranStatus::UnKnown;

        if (TranStatus::UnKnown === $this->tranStatus) {
            PaytabsSDK::getLogger()->error('Unknown transaction status', [
                'tran_status' => $respStatus,
            ]);
        }
    }
}
