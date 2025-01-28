<?php

namespace Paytabs\Sdk\Response;

interface ResponseInterface
{
    public function setResponse($raw_response);

    public function setPayload(PayloadInterface $payloadClass);

    public function getPayload(): ?PayloadInterface;
}
