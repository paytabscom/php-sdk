<?php

namespace Response\Payloads;

class CompletedReturn extends Paytabs
{
    public string $acquirerMessage;
    public string $acquirerRRN;
    public string $cartId; // cart_11111
    public string $customerEmail; // email%40domain.com
    public string $respCode; // G04658
    public string $respMessage; // Authorised
    public string $respStatus; // H
    public string $signature; // 301283e2581e8b5e42c386bc2cb9df094a759cdb7cec358dcfd217bb7dda2987
    public string $token;
    public string $tranRef; // TST2436002183964
}
