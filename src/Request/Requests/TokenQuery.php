<?php

namespace Paytabs\Sdk\Request\Requests;

use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Request\Payload\Payloads\Token\Token;
use Paytabs\Sdk\Request\PaytabsRequest;
use Paytabs\Sdk\Response\Payload\PayloadInterface;
use Paytabs\Sdk\Response\Payload\Payloads\Payment\Completed;

class TokenQuery extends PaytabsRequest
{
    protected string $path = 'payment/token';

    public function __construct(
        Profile $profile,
        Token $holder
    ) {
        parent::__construct($profile, $holder);
    }

    /** @return Completed */
    public function getResponseClass(): PayloadInterface
    {
        return new Completed();
    }
}
