<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Exceptions;

final class UnknownResponseValueException extends \RuntimeException
{
    public static function forTranType(string $tranType): self
    {
        return new self('Unknown transaction type: '.$tranType);
    }

    public static function forTranClass(string $tranClass): self
    {
        return new self('Unknown transaction class: '.$tranClass);
    }

    public static function forTranStatus(string $tranStatus): self
    {
        return new self('Unknown transaction status: '.$tranStatus);
    }
}
