<?php

namespace Paytabs\Sdk\Response\Responses\Webhook;

use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Response\AbstractResponseWebhook;
use Psr\Log\LoggerInterface;

abstract class TransactionResult extends AbstractResponseWebhook
{
    protected array $headers;

    /** Query params those had been set with the URLs (Return/Callback) */
    protected array $localParams;

    protected Profile $profile;

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

    public function setProfile(Profile $profile): self
    {
        $this->profile = $profile;

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
                'server_key_hint' => $hashed_key,
                'generated_signature_prefix' => substr($signature, 0, 8),
                'request_signature_prefix' => substr($requestSignature, 0, 8),
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
        return $this->profile->getServerKey();
    }
}
