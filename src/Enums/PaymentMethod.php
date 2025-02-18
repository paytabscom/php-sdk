<?php

namespace Paytabs\Sdk\Enums;

use Paytabs\Sdk\PaymentMethod\Methods\All;
use Paytabs\Sdk\PaymentMethod\Methods\ApplePay;
use Paytabs\Sdk\PaymentMethod\Methods\Card;
use Paytabs\Sdk\PaymentMethod\Methods\Fawry;
use Paytabs\Sdk\PaymentMethod\Methods\Sadad;

class PaymentMethod extends EnumString
{
    public const All = All::class;
    public const ApplePay = ApplePay::class;
    public const Card = Card::class;
    public const Fawry = Fawry::class;
    public const Sadad = Sadad::class;

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

    public static function getAllMethods(): array
    {
        $refl = new \ReflectionClass(PaymentMethod::class);
        $all = $refl->getConstants();

        $cases = [];
        foreach ($all as $key => $value) {
            $enum = new \stdClass();

            $enum->key = $key;
            $enum->value = $value;

            $cases[] = $enum;
        }

        return $cases;
    }
}
