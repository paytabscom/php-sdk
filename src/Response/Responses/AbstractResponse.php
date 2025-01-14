<?php

namespace Paytabs\Sdk\Response\Responses;

use Paytabs\Sdk\Gateway\Gateway;
use Paytabs\Sdk\Response\Payloads\Callbacks\Browser;
use Psr\Log\LoggerInterface;

abstract class AbstractResponse
{
    protected string $response;

    protected array $headers;

    protected Gateway $gateway;

    protected LoggerInterface $logger;

    //

    public function __construct(string $response, array $headers = [])
    {
        $this->response = $response;
        $this->headers = $headers;
    }

    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;
        return $this;
    }

    public function setGateway(Gateway $gateway): self
    {
        $this->gateway = $gateway;
        return $this;
    }

    public function getRaw(): string
    {
        return $this->response;
    }

    public function getJson()
    {
        return json_decode($this->getRaw());
    }

    //

    abstract public function isValid(): bool;

    final protected function isGenuine($data, $requestSignature, $serverKey)
    {
        $signature = hash_hmac('sha256', $data, $serverKey);

        if (hash_equals($signature, $requestSignature) === TRUE) {
            // VALID Redirect
            return true;
        } else {
            // INVALID Redirect
            return false;
        }
    }
}
