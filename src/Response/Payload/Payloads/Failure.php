<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Payload\Payloads;

use Paytabs\Sdk\Exceptions\GatewayFailureException;

class Failure extends Paytabs
{
    public ?int $code = null;
    public ?string $message = null;

    /**
     * @throws GatewayFailureException always
     */
    public function throwException(): void
    {
        throw GatewayFailureException::fromResponse($this->code, $this->message);
    }

    /*
    401
    "code": 1,
    "message": "Authentication failed. Check authentication header.",
    "trace": "PMNT0402.676A9614.000668D2"
    */

    /*
    400
    "code": 206,
    "message": "Invalid currency code",
    "trace": "PMNT0401.676A9671.00066A91"
    */

    /*
    409
    "code": 4,
    "message": "Duplicate Request",
    "trace": "PMNT0101.676AACB7.002BDDF1"
    */
}
