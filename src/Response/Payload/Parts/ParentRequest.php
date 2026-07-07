<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Payload\Parts;

class ParentRequest
{
    // "parentRequest"

    public string $tran_ref;

    public float $cart_amount;
    public string $cart_currency;
}
