<?php

namespace Paytabs\Sdk\Request\Requests;

use Paytabs\Sdk\Gateway\Gateway;
use Paytabs\Sdk\Request\Payload\Payloads\Token\Token;
use Paytabs\Sdk\Request\PaytabsRequest;
use Paytabs\Sdk\Response\Payload\PayloadInterface;
use Paytabs\Sdk\Response\Payload\Payloads\Payment\Completed;

class TokenQuery extends PaytabsRequest
{
    protected string $path = 'payment/token';

    public function __construct(
        Gateway $environment,
        Token $holder
    ) {
        parent::__construct($environment, $holder);
    }

    /** @return Completed */
    public function getResponseClass(): PayloadInterface
    {
        return new Completed();
    }
}
