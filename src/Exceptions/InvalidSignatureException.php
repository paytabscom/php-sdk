<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Exceptions;

final class InvalidSignatureException extends \RuntimeException
{
    public static function mismatch(string $keyPrefix): self
    {
        return new self('Invalid signature, ' . $keyPrefix);
    }
}
