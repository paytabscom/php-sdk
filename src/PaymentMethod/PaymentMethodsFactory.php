<?php

namespace Paytabs\Sdk\PaymentMethod;

use Paytabs\Sdk\Enums\PaymentMethod;
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

abstract class PaymentMethodsFactory
{
    private static array $methodsMapper = [];

    public static function createMethod(string $code): AbstractMethod
    {
        $instance = static::findMethodClass($code);

        if (!$instance) {
            throw new \RuntimeException("Payment Method not found for Code: {$code}");
        }

        return $instance;
    }

    public static function createMethodById(int $id): AbstractMethod
    {
        $instance = static::findMethodClassById($id);

        if (!$instance) {
            throw new \RuntimeException("Payment Method not found for ID: {$id}");
        }

        return $instance;
    }

    public static function createMethodByUnique(string $pt_code): AbstractMethod
    {
        $instance = static::findMethodClassByUnique($pt_code);

        if (!$instance) {
            throw new \RuntimeException("Payment Method not found for Unique Code: {$pt_code}");
        }

        return $instance;
    }

    public static function getAllCurrencies(): array
    {
        $allMethods = self::getMethodsMapper();

        $currencies = [];
        foreach ($allMethods as $method) {
            $currencies = array_merge($currencies, $method::supportedCurrencies());
        }

        return array_unique($currencies);
    }

    // Create specific methods for known payment methods

    public static function createAmanMethod(): Aman
    {
        return new Aman();
    }

    public static function createAmanInstallmentsMethod(): AmanInstallments
    {
        return new AmanInstallments();
    }

    public static function createAmexMethod(): Amex
    {
        return new Amex();
    }

    public static function createApplePayMethod(): ApplePay
    {
        return new ApplePay();
    }

    public static function createBasataMethod(): Basata
    {
        return new Basata();
    }

    public static function createCardMethod(): Card
    {
        return new Card();
    }

    public static function createFawryMethod(): Fawry
    {
        return new Fawry();
    }

    public static function createForsaMethod(): Forsa
    {
        return new Forsa();
    }

    public static function createHalanMethod(): Halan
    {
        return new Halan();
    }

    public static function createInstallmentsMethod(): Installments
    {
        return new Installments();
    }

    public static function createKNetMethod(): KNet
    {
        return new KNet();
    }

    public static function createKnetCreditMethod(): KNetCredit
    {
        return new KNetCredit();
    }

    public static function createKnetDebitMethod(): KNetDebit
    {
        return new KNetDebit();
    }

    public static function createMadaMethod(): Mada
    {
        return new Mada();
    }

    public static function createMeezaMethod(): Meeza
    {
        return new Meeza();
    }

    public static function createMeezaQRMethod(): MeezaQR
    {
        return new MeezaQR();
    }

    public static function createOmanNetMethod(): OmanNet
    {
        return new OmanNet();
    }

    public static function createPayPalMethod(): PayPal
    {
        return new PayPal();
    }

    public static function createPayTabsAllMethod(): PayTabsAll
    {
        return new PayTabsAll();
    }

    public static function createSadadMethod(): Sadad
    {
        return new Sadad();
    }

    public static function createSamsungPayMethod(): SamsungPay
    {
        return new SamsungPay();
    }

    public static function createSouhoolaMethod(): Souhoola
    {
        return new Souhoola();
    }

    public static function createStcPayMethod(): StcPay
    {
        return new StcPay();
    }

    public static function createStcPayQRMethod(): StcPayQR
    {
        return new StcPayQR();
    }

    public static function createTabbyMethod(): Tabby
    {
        return new Tabby();
    }

    public static function createTamaraMethod(): Tamara
    {
        return new Tamara();
    }

    public static function createTouchpointsMethod(): Touchpoints
    {
        return new Touchpoints();
    }

    public static function createTruMethod(): Tru
    {
        return new Tru();
    }

    public static function createUnionPayMethod(): UnionPay
    {
        return new UnionPay();
    }

    public static function createUrPayMethod(): UrPay
    {
        return new UrPay();
    }

    public static function createValUMethod(): ValU
    {
        return new ValU();
    }

    // End Create specific methods for known payment methods

    /** @return AbstractMethod[] */
    private static function getMethodsMapper(): array
    {
        if (empty(static::$methodsMapper)) {
            $allMethods = PaymentMethod::getAllMethods();

            static::$methodsMapper = array_map(static fn ($enumMethod) => new $enumMethod->value(), $allMethods);
        }

        return static::$methodsMapper;
    }

    private static function findMethodClass(string $code): ?AbstractMethod
    {
        $allMethods = self::getMethodsMapper();

        foreach ($allMethods as $method) {
            if ($method->matchesCode($code)) {
                return $method;
            }
        }

        return null;
    }

    private static function findMethodClassById(int $id): ?AbstractMethod
    {
        $allMethods = self::getMethodsMapper();

        foreach ($allMethods as $method) {
            if ($method::ID === $id) {
                return $method;
            }
        }

        return null;
    }

    private static function findMethodClassByUnique(string $pt_code): ?AbstractMethod
    {
        $allMethods = self::getMethodsMapper();

        foreach ($allMethods as $method) {
            if (0 === strcasecmp($method::PT_CODE, $pt_code)) {
                return $method;
            }
        }

        return null;
    }
}
