<?php

namespace Paytabs\Sdk\Holder\Parts;

use Paytabs\Sdk\Helpers\NextIf;
use Paytabs\Sdk\Helpers\NextIfInterface;
use Paytabs\Sdk\Holder\PartInterface;

abstract class AbstractPart implements PartInterface, NextIfInterface
{
    use NextIf;

    public static function init(): static
    {
        return new static();
    }
}
