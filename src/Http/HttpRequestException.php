<?php

namespace Paytabs\Sdk\Http;

class HttpRequestException extends \RuntimeException
{
    public static function transport(string $message, int $code = 0): self
    {
        return new self('cURL transport error: '.$message, $code);
    }

    public static function invalidStatusCode(int $statusCode): self
    {
        return new self(sprintf('HTTP request failed with status code %d', $statusCode), $statusCode);
    }
}
