<?php

namespace Paytabs\Sdk\Response\Responses\Webhook;

use Paytabs\Sdk\Gateway\Gateway;
use Paytabs\Sdk\Response\AbstractResponseWebhook;
use Psr\Log\LoggerInterface;

abstract class TransactionResult extends AbstractResponseWebhook
{
    protected array $headers;

    /** Query params those had been set with the URLs (Return/Callback) */
    protected array $localParams;

    protected Gateway $gateway;

    protected LoggerInterface $logger;

    public function __construct(?string $response, array $headers = [], array $localParams = [])
    {
        parent::setResponse($response);

        $this->headers = $headers;
        $this->localParams = $localParams;
    }

    abstract public static function init(): self;

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

        $signature = hash_hmac('sha256', $data, $serverKey);

        if (true === hash_equals($signature, $requestSignature)) {
            // VALID Redirect
            return true;
        }
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

    /**
     * Check if it is a valid response (contains all required fields).
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
}
