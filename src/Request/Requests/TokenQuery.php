<?php

namespace Paytabs\Sdk\Request\Requests;

use Gateway\Gateway;
use Holder\Builders\Token\Token;
use Request\PaytabsRequest;
use Response\Payloads\Payment\Completed;
use Response\PayloadInterface;

class TokenQuery extends PaytabsRequest
{
    protected string $path = 'payment/token';

    //

    public function __construct(
        Gateway $environment,
        Token $holder
    ) {
        parent::__construct($environment, $holder);
    }

    //

    /** @return Completed */
    public function getResponseClass(): PayloadInterface
    {
        return new Completed();
    }
}
