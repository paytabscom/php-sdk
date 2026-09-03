<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Exceptions;

final class InvalidSignatureException extends \RuntimeException implements PaytabsExceptionInterface
{
    /**
     * @param string $keyHint non-reversible hint from Profile::getServerKeyPrefix()
     */
    public static function mismatch(string $keyHint): self
    {
        return new self('Invalid signature (server key hint: ' . $keyHint . ')');
    }
}
