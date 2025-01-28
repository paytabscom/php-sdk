<?php

namespace Paytabs\Sdk\Enums;

use Exception;

abstract class EnumString extends Enum
{
    public function __construct(string $value)
    {
        if (!$this->isValidValue($value)) {
            throw new Exception('Not a valid Enum value');
        }

        $this->value = $value;
    }

    public static function from($value)
    {
        return new static($value);
    }

    public static function tryFrom($value)
    {
        try {
            return static::from($value);
        } catch (\Throwable $th) {
            return null;
        }
    }
}
