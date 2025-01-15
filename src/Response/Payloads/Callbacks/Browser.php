<?php

namespace Paytabs\Sdk\Response\Payloads\Callbacks;

use Paytabs\Sdk\Enums\TranStatus;
use Paytabs\Sdk\Response\Payloads\Paytabs;

class Browser extends Paytabs
{
    public string $acquirerMessage;
    public string $acquirerRRN;

    public string $tranRef; // TST2436002183964
    public string $cartId; // cart_11111

    public string $customerEmail; // email%40domain.com

    public string $respStatus; // H
    public TranStatus $tranStatus;

    public string $respMessage; // Authorised
    public string $respCode; // G04658

    public string $signature; // 301283e2581e8b5e42c386bc2cb9df094a759cdb7cec358dcfd217bb7dda2987

    public string $token;

    //

    public function setRespStatus(string $respStatus)
    {
        $this->respStatus = $respStatus;
        $this->tranStatus = TranStatus::tryFrom(strtoupper($respStatus));
    }
}
