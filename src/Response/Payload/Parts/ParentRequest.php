<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Payload\Parts;

class ParentRequest
{
    // "parentRequest"

    public ?string $tran_ref = null;

    public ?float $cart_amount = null;
    public ?string $cart_currency = null;
}
