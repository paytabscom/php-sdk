<?php

namespace Paytabs\Sdk\Enums;

abstract class EnumInt extends Enum
{
    public function __construct(int $value)
    {
        if (!$this->isValidValue($value)) {
            throw new \Exception('Not a valid Enum value');
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
            return EnumInt::from($value);
        } catch (\Throwable $th) {
            return null;
        }
    }
}
