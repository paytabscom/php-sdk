<?php

namespace Paytabs\Sdk\Enums;

use ReflectionClass;

abstract class Enum
{
    public $value = null;

    private static $constCacheArray = NULL;

    private static function getConstants()
    {
        if (static::$constCacheArray == NULL) {
            static::$constCacheArray = [];
        }
        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, static::$constCacheArray)) {
            $reflect = new ReflectionClass($calledClass);
            static::$constCacheArray[$calledClass] = $reflect->getConstants();
        }
        return static::$constCacheArray[$calledClass];
    }

    public static function isValidName($name, $strict = false)
    {
        $constants = static::getConstants();

        if ($strict) {
            return array_key_exists($name, $constants);
        }

        $keys = array_map('strtolower', array_keys($constants));
        return in_array(strtolower($name), $keys);
    }

    public static function isValidValue($value, $strict = true)
    {
        $values = array_values(static::getConstants());
        return in_array($value, $values, $strict);
    }
}
