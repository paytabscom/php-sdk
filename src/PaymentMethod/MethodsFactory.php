<?php

namespace Paytabs\Sdk\PaymentMethod;

use Paytabs\Sdk\Enums\PaymentMethod;
use Paytabs\Sdk\PaymentMethod\Methods\ApplePay;
use Paytabs\Sdk\PaymentMethod\Methods\Card;
use Paytabs\Sdk\PaymentMethod\Methods\Fawry;
use Paytabs\Sdk\PaymentMethod\Methods\PayTabsAll;
use Paytabs\Sdk\PaymentMethod\Methods\Sadad;

abstract class MethodsFactory
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

    /** @return AbstractMethod[] */
    private static function getMethodsMapper(): array
    {
        if (empty(static::$methodsMapper)) {
            $allMethods = PaymentMethod::getAllMethods();

            static::$methodsMapper = array_map(static fn($enumMethod) => new $enumMethod->value(), $allMethods);
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

    //

    public static function createPayTabsAllMethod(): PayTabsAll
    {
        return new PayTabsAll();
    }

    public static function createCardMethod(): Card
    {
        return new Card();
    }

    public static function createApplePayMethod(): ApplePay
    {
        return new ApplePay();
    }

    public static function createSadadMethod(): Sadad
    {
        return new Sadad();
    }

    public static function createFawryMethod(): Fawry
    {
        return new Fawry();
    }
}
