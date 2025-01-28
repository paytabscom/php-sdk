<?php

namespace Paytabs\Sdk\Enums;

use Paytabs\Sdk\PaymentMethod\Methods\All;
use Paytabs\Sdk\PaymentMethod\Methods\ApplePay;
use Paytabs\Sdk\PaymentMethod\Methods\Card;
use Paytabs\Sdk\PaymentMethod\Methods\Fawry;
use Paytabs\Sdk\PaymentMethod\Methods\Sadad;
use ReflectionClass;

class PaymentMethod extends EnumString
{
    const All = All::class;
    const ApplePay = ApplePay::class;
    const Card = Card::class;
    const Fawry = Fawry::class;
    const Sadad = Sadad::class;

    public static function All()
    {
        return new self(self::All);
    }

    public static function ApplePay()
    {
        return new self(self::ApplePay);
    }

    public static function Card()
    {
        return new self(self::Card);
    }

    public static function Fawry()
    {
        return new self(self::Fawry);
    }

    public static function Sadad()
    {
        return new self(self::Sadad);
    }

    //

    public static function getAllMethods(): array
    {
        $refl = new ReflectionClass(PaymentMethod::class);
        return $refl->getConstants();
    }
}
