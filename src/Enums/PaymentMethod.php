<?php

namespace Paytabs\Sdk\Enums;

use Paytabs\Sdk\PaymentMethod\Methods\All;
use Paytabs\Sdk\PaymentMethod\Methods\ApplePay;
use Paytabs\Sdk\PaymentMethod\Methods\Card;
use Paytabs\Sdk\PaymentMethod\Methods\Fawry;
use Paytabs\Sdk\PaymentMethod\Methods\Sadad;

enum PaymentMethod: string
{
    case All = All::class;
    case ApplePay = ApplePay::class;
    case Card = Card::class;
    case Fawry = Fawry::class;
    case Sadad = Sadad::class;

    //

    public static function getAllMethods(): array
    {
        return PaymentMethod::cases();
    }
}
