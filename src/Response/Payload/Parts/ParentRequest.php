<?php

namespace Paytabs\Sdk\Response\Payload\Parts;

class ParentRequest
{
    // "parentRequest"

    public string $tran_ref;

    public float $cart_amount;
    public string $cart_currency;
}
