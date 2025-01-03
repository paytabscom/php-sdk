<?php

namespace Response\Payloads;

use Enums\TranType;
use Holder\Parts\CustomerDetails;
use Holder\Parts\ShippingDetails;

abstract class Payment extends Paytabs
{
    public int $merchantId;
    public int $profileId;

    public string $tran_ref;
    public string $tran_type;
    public TranType $tranType;

    public string $cart_id;
    public string $cart_description;
    public string $cart_currency;
    public float $cart_amount;
    public float $tran_total;

    public CustomerDetails $customer_details;
    public ShippingDetails $shipping_details;

    //

    public function setTranType(string $tran_type)
    {
        $this->tran_type = $tran_type;
        $this->tranType = TranType::tryFrom(strtolower($tran_type));
    }
}
