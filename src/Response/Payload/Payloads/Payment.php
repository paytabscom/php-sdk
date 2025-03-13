<?php

namespace Paytabs\Sdk\Response\Payload\Payloads;

use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Request\Payload\Parts\CustomerDetails;
use Paytabs\Sdk\Request\Payload\Parts\ShippingDetails;
use Paytabs\Sdk\Request\Payload\Parts\UserDefined;
use Paytabs\Sdk\Paytabs as PaytabsSDK;
use Paytabs\Sdk\Response\Payload\Parts\Invoice;

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

    public string $customer_ref;

    public Invoice $invoice;

    public CustomerDetails $customer_details;
    public ShippingDetails $shipping_details;
    public UserDefined $user_defined;

    public string $paymentChannel;

    public function setTranType(string $tran_type)
    {
        $this->tran_type = $tran_type;
        $this->tranType = TranType::tryFrom(strtolower($tran_type)) ?? TranType::UnKnown;

        if (TranType::UnKnown === $this->tranType) {
            PaytabsSDK::getLogger()->error('Unknown transaction type', [
                'tran_type' => $tran_type,
            ]);
        }
    }
}
