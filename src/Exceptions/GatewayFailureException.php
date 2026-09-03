<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Exceptions;

/**
 * The gateway rejected the request and returned a `code`/`message` object.
 *
 * A declined payment is not this: it arrives as a normal completed response
 * with a `payment_result`. This is a request-level refusal — authentication
 * failure, invalid currency, duplicate request.
 *
 * The gateway's numeric `code` is exposed via getGatewayCode() rather than the
 * exception code, because it is not unique per condition (`2` covers several
 * distinct invoice failures) and must not be parsed as one.
 */
final class GatewayFailureException extends \RuntimeException implements PaytabsExceptionInterface
{
    private ?int $gatewayCode = null;

    public static function fromResponse(?int $code, ?string $message): self
    {
        $exception = new self(\sprintf(
            'PayTabs rejected the request (code %s): %s',
            $code ?? 'unknown',
            $message ?? 'no message returned'
        ));

        $exception->gatewayCode = $code;

        return $exception;
    }

    public function getGatewayCode(): ?int
    {
        return $this->gatewayCode;
    }
}
