<?php

namespace Paytabs\Sdk\Enums;

use Paytabs\Sdk\PaymentMethod\AbstractMethod;
use Paytabs\Sdk\PaymentMethod\Methods\Aman;
use Paytabs\Sdk\PaymentMethod\Methods\AmanInstallments;
use Paytabs\Sdk\PaymentMethod\Methods\Amex;
use Paytabs\Sdk\PaymentMethod\Methods\ApplePay;
use Paytabs\Sdk\PaymentMethod\Methods\Basata;
use Paytabs\Sdk\PaymentMethod\Methods\Card;
use Paytabs\Sdk\PaymentMethod\Methods\Fawry;
use Paytabs\Sdk\PaymentMethod\Methods\Forsa;
use Paytabs\Sdk\PaymentMethod\Methods\Halan;
use Paytabs\Sdk\PaymentMethod\Methods\Installments;
use Paytabs\Sdk\PaymentMethod\Methods\KNet;
use Paytabs\Sdk\PaymentMethod\Methods\KNetCredit;
use Paytabs\Sdk\PaymentMethod\Methods\KNetDebit;
use Paytabs\Sdk\PaymentMethod\Methods\Mada;
use Paytabs\Sdk\PaymentMethod\Methods\Meeza;
use Paytabs\Sdk\PaymentMethod\Methods\MeezaQR;
use Paytabs\Sdk\PaymentMethod\Methods\OmanNet;
use Paytabs\Sdk\PaymentMethod\Methods\PayPal;
use Paytabs\Sdk\PaymentMethod\Methods\PayTabsAll;
use Paytabs\Sdk\PaymentMethod\Methods\Sadad;
use Paytabs\Sdk\PaymentMethod\Methods\SamsungPay;
use Paytabs\Sdk\PaymentMethod\Methods\Souhoola;
use Paytabs\Sdk\PaymentMethod\Methods\StcPay;
use Paytabs\Sdk\PaymentMethod\Methods\StcPayQR;
use Paytabs\Sdk\PaymentMethod\Methods\Tabby;
use Paytabs\Sdk\PaymentMethod\Methods\Tamara;
use Paytabs\Sdk\PaymentMethod\Methods\Touchpoints;
use Paytabs\Sdk\PaymentMethod\Methods\Tru;
use Paytabs\Sdk\PaymentMethod\Methods\UnionPay;
use Paytabs\Sdk\PaymentMethod\Methods\UrPay;
use Paytabs\Sdk\PaymentMethod\Methods\ValU;

enum PaymentMethod: string
{
    case PayTabsAll = PayTabsAll::class;
    case ApplePay = ApplePay::class;
    case Card = Card::class;
    case Fawry = Fawry::class;
    case Sadad = Sadad::class;
    case Aman = Aman::class;
    case AmanInstallments = AmanInstallments::class;
    case Amex = Amex::class;
    case Basata = Basata::class;
    case Forsa = Forsa::class;
    case Halan = Halan::class;
    case Installments = Installments::class;
    case KNet = KNet::class;
    case KNetCredit = KNetCredit::class;
    case KNetDebit = KNetDebit::class;
    case Mada = Mada::class;
    case Meeza = Meeza::class;
    case MeezaQR = MeezaQR::class;
    case OmanNet = OmanNet::class;
    case PayPal = PayPal::class;
    case SamsungPay = SamsungPay::class;
    case Souhoola = Souhoola::class;
    case StcPay = StcPay::class;
    case StcPayQR = StcPayQR::class;
    case Tabby = Tabby::class;
    case Tamara = Tamara::class;
    case Touchpoints = Touchpoints::class;
    case Tru = Tru::class;
    case UnionPay = UnionPay::class;
    case UrPay = UrPay::class;
    case ValU = ValU::class;

    public static function getAllMethods(): array
    {
        return PaymentMethod::cases();
    }

    public function getMethodInstance(): AbstractMethod
    {
        return new $this->value();
    }
}
