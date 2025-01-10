<?php

namespace Response\Parts;

class PaymentInfo
{
    /*
    "payment_info": {
    */
    public string $payment_method; // "Visa"
    public string $card_type; // "Credit"
    public string $card_scheme; // "Visa"
    public string $payment_description; // "4111 11## #### 1111",
    public int $expiryMonth; // 11,
    public int $expiryYear; // 2033
}
