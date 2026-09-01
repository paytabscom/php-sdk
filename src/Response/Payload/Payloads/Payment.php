<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Payload\Payloads;

use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Exceptions\UnknownResponseValueException;
use Paytabs\Sdk\Request\Payload\Parts\CustomerDetails;
use Paytabs\Sdk\Request\Payload\Parts\ShippingDetails;
use Paytabs\Sdk\Request\Payload\Parts\UserDefined;
use Paytabs\Sdk\Response\Payload\Parts\Invoice;

abstract class Payment extends Paytabs
{
    public ?int $merchantId = null;
    public ?int $profileId = null;

    public ?string $tran_ref = null;
    public ?string $tran_type = null;
    public ?TranType $tranType = null;

    public ?string $cart_id = null;
    public ?string $cart_description = null;
    public ?string $cart_currency = null;
    public ?float $cart_amount = null;
    public ?float $tran_total = null;

    public ?string $customer_ref = null;

    public ?Invoice $invoice = null;

    public ?CustomerDetails $customer_details = null;
    public ?ShippingDetails $shipping_details = null;
    public ?UserDefined $user_defined = null;

    public ?string $paymentChannel = null;

    public function setTranType(string $tran_type): void
    {
        $this->tran_type = $tran_type;
        $this->tranType = TranType::tryFrom(strtolower($tran_type)) ?? TranType::Unknown;

        if (TranType::Unknown === $this->tranType) {
            static::logger()->error('Unknown transaction type', [
                'tran_type' => $tran_type,
            ]);

            if (self::isStrictMode()) {
                throw UnknownResponseValueException::forTranType($tran_type);
            }
        }
    }
}
