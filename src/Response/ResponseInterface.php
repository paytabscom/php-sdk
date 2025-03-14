<?php

namespace Paytabs\Sdk\Response;

use Paytabs\Sdk\Response\Payload\PayloadInterface;

interface ResponseInterface
{
    public function setResponse($raw_response);

    public function setPayload(PayloadInterface $payloadClass);

    public function getPayload(): ?PayloadInterface;
}
