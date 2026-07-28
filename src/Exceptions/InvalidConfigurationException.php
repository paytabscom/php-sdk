<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Exceptions;

class InvalidConfigurationException extends \RuntimeException
{
    public static function missing(string $key): self
    {
        return new self(\sprintf(
            'PayTabs SDK:: Invalid value of key: [%s].',
            $key,
        ));
    }
}
