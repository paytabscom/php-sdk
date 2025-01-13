<?php

namespace Paytabs\Sdk;

abstract class Paytabs
{
    const VERSION = '3.0.0';

    final public static function getVersion(): string
    {
        return Paytabs::VERSION;
    }
}
