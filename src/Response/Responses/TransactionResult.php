<?php

namespace Paytabs\Sdk\Response\Responses;

use Paytabs\Sdk\Gateway\Gateway;
use Psr\Log\LoggerInterface;

abstract class TransactionResult
{
    protected ?string $response;

    protected array $headers;

    /** Query params those had been set with the URLs (Return/Callback) */
    protected array $localParams;

    protected Gateway $gateway;

    protected LoggerInterface $logger;

    //

    abstract public static function init(): self;

    public function __construct(?string $response, array $headers = [], array $localParams = [])
    {
        $this->response = $response;
        $this->headers = $headers;
        $this->localParams = $localParams;
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

    public function getRaw(): ?string
    {
        return $this->response;
    }

    public function getJson()
    {
        if (!$this->getRaw()) {
            return null;
        }
        return json_decode($this->getRaw());
    }

    //

    abstract public function isValid(): bool;

    final protected function isGenuine($data, $requestSignature, $serverKey)
    {
        $signature = hash_hmac('sha256', $data, $serverKey);

        if (hash_equals($signature, $requestSignature) === true) {
            // VALID Redirect
            return true;
        } else {
            // INVALID Redirect
            return false;
        }
    }
}
