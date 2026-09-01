<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Payload\Parts;

class PaymentInfo
{
    // "payment_info": {
    public ?string $payment_method = null; // "Visa"
    public ?string $card_type = null; // "Credit"
    public ?string $card_scheme = null; // "Visa"
    public ?string $payment_description = null; // "4111 11## #### 1111",
    public ?int $expiryMonth = null; // 11,
    public ?int $expiryYear = null; // 2033
}
