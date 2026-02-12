<?php

namespace Paytabs\Sdk\Enums;

use Paytabs\Sdk\PaymentMethod\Methods\ApplePay;
use Paytabs\Sdk\PaymentMethod\Methods\Card;
use Paytabs\Sdk\PaymentMethod\Methods\Fawry;
use Paytabs\Sdk\PaymentMethod\Methods\PayTabsAll;
use Paytabs\Sdk\PaymentMethod\Methods\Sadad;

enum PaymentMethod: string
{
    case PayTabsAll = PayTabsAll::class;
    case ApplePay = ApplePay::class;
    case Card = Card::class;
    case Fawry = Fawry::class;
    case Sadad = Sadad::class;

    public static function getAllMethods(): array
    {
        return PaymentMethod::cases();
    }
}
