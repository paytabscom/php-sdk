<?php

namespace Paytabs\Sdk\Response\Responses\Webhook;

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

    /**
     * Check if it is a valid response (contains all required fields)
     */
    abstract protected function isValid(): bool;

    /**
     * @return string The payload that should be hashed
     */
    abstract protected function prepareHashablePayload(): string;

    /**
     * @return string The hashed response came from the server
     */
    abstract protected function getServerSignature(): string;

    protected function getServerKey(): string
    {
        return $this->gateway->getServerKey();
    }

    /**
     * @return bool if the response is genuine from the server
     */
    final public function isGenuine(): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        $data = $this->prepareHashablePayload();
        $requestSignature = $this->getServerSignature();
        $serverKey = $this->getServerKey();

        //

        $signature = hash_hmac('sha256', $data, $serverKey);

        if (hash_equals($signature, $requestSignature) === true) {
            // VALID Redirect
            return true;
        } else {
            // INVALID Redirect

            if (isset($this->logger)) {
                $hashed_key = explode('-', $serverKey ?? '')[0];
                $this->logger->alert('Invalid signature', [
                    $hashed_key,
                    $signature,
                    $requestSignature,
                ]);
            }

            return false;
        }
    }
}
