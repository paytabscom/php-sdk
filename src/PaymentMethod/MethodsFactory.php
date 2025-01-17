<?php

namespace Paytabs\Sdk\PaymentMethod;

use Exception;
use Paytabs\Sdk\Enums\PaymentMethod;

abstract class MethodsFactory
{
    public static function createMethod(string $code): AbstractMethod
    {
        $instance = static::findMethodClass($code);

        if (!$instance) {
            throw new Exception("Payment Method not found for Code: $code");
        }

        return $instance;
    }

    //

    private static array $methodsMapper = [];

    /** @return AbstractMethod[] */
    private static function getMethodsMapper(): array
    {
        if (empty(static::$methodsMapper)) {
            $allMethods = PaymentMethod::getAllMethods();

            static::$methodsMapper = array_map(static function ($enumMethod) {
                return new $enumMethod->value;
            }, $allMethods);
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
}
