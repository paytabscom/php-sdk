<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Exceptions;

final class HttpRequestException extends \RuntimeException implements PaytabsExceptionInterface
{
    public static function transport(string $message, int $code = 0): self
    {
        return new self('cURL transport error: ' . $message, $code);
    }

    public static function invalidStatusCode(int $statusCode): self
    {
        return new self(\sprintf('HTTP request failed with status code %d', $statusCode), $statusCode);
    }

    /**
     * Non-2xx whose body is not JSON — typically an HTML error page from a CDN
     * or WAF. Raised from the transport layer so consumers can catch this
     * alongside every other HTTP failure; it previously escaped as a bare
     * \JsonException thrown from deep inside the payload layer.
     */
    public static function unexpectedResponseBody(int $statusCode, string $body): self
    {
        return new self(
            \sprintf(
                'HTTP request failed with status code %d and a non-JSON body: %s',
                $statusCode,
                self::excerpt($body)
            ),
            $statusCode
        );
    }

    public static function payloadNotEncodable(string $reason): self
    {
        return new self('Request payload could not be encoded as JSON: ' . $reason);
    }

    private static function excerpt(string $body, int $limit = 200): string
    {
        $body = trim(preg_replace('/\s+/', ' ', $body) ?? '');

        if ('' === $body) {
            return '(empty)';
        }

        return \strlen($body) > $limit
            ? substr($body, 0, $limit) . '…'
            : $body;
    }
}
