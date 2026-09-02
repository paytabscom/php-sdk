<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Responses\Webhook;

use Paytabs\Sdk\Enums\TranStatus;
use Paytabs\Sdk\Exceptions\InvalidConfigurationException;
use Paytabs\Sdk\Exceptions\InvalidSignatureException;
use Paytabs\Sdk\Exceptions\MissingResponseFieldException;
use Paytabs\Sdk\Logger\Redactor;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Response\AbstractResponseWebhook;
use Psr\Log\LoggerInterface;

abstract class AbstractTransactionResult extends AbstractResponseWebhook
{
    protected array $headers;

    /** Query params those had been set with the URLs (Return/Callback) */
    protected array $localParams;

    protected ?Profile $profile = null;

    protected ?LoggerInterface $logger = null;


    /**
     * @throws \InvalidArgumentException if the payload is not valid or cannot be mapped.
     * @throws \LogicException           if the payload is not initialized
     */
    public function __construct(?string $response, array $headers = [], array $localParams = [])
    {
        if (null === $response) {
            throw new \InvalidArgumentException('Invalid callback payload: no response data');
        }

        parent::setResponse($response);

        $this->headers = $headers;
        $this->localParams = $localParams;

        $this->mapPayload();
    }

    /**
     * @throws \InvalidArgumentException if the payload is not valid or cannot be mapped.
     * @throws \LogicException           if the payload is not initialized
     */
    private function mapPayload(): void
    {
        if (null === $this->payload) {
            throw new \LogicException('Payload is not initialized.');
        }

        $this->payload->getMapped();
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

        if (!$this->isSameProfile()) {
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

        // A forged callback is unauthenticated and repeatable, so this branch is
        // attacker-reachable: the hint must be a one-way digest, never a slice
        // of the live server key.
        $context = [
            'server_key_hint' => Redactor::keyHint($serverKey),
            'generated_signature_prefix' => substr($signature, 0, 8),
            'request_signature_prefix' => substr($requestSignature, 0, 8),
        ];

        $this->log('Invalid signature', $context);

        return false;
    }

    /**
     * Fail-closed counterpart to isGenuine(): verifies the signature and throws
     * instead of returning false, so a forged callback cannot be processed by
     * forgetting an `if`.
     *
     * @throws InvalidSignatureException     when the signature does not verify
     * @throws InvalidConfigurationException when no profile was set
     */
    final public function assertGenuine(): static
    {
        if (!$this->isGenuine()) {
            throw InvalidSignatureException::mismatch(
                Redactor::keyHint($this->getServerKey())
            );
        }

        return $this;
    }

    public function log(string $message, array $context = []): void
    {
        if (null !== $this->logger) {
            $this->logger->alert($message, $context);
        } else {
            // A rejected callback must always leave a trace. setLogger() is
            // optional, so without this fallback a forged webhook would be
            // dropped with no audit trail at all.
            error_log('PayTabs SDK: ' . $message . ' - ' . json_encode($context));
        }
    }

    abstract public function getTranRef(): string;

    abstract public function getCartId(): string;

    abstract public function getTranStatus(): TranStatus;

    /**
     * Check if it is a valid response (contains all required fields).
     */
    abstract protected function isValid(): bool;

    abstract protected function isSameProfile(): bool;

    /**
     * @return string The payload that should be hashed
     */
    abstract protected function prepareHashablePayload(): string;

    /**
     * @return string The hashed response came from the server
     */
    abstract protected function getServerSignature(): string;

    /**
     * @throws MissingResponseFieldException when the gateway omitted the field
     */
    protected static function required(mixed $value, string $field): mixed
    {
        if (null === $value) {
            throw MissingResponseFieldException::forField($field);
        }

        return $value;
    }

    protected function getServerKey(): string
    {
        if (null === $this->profile) {
            throw InvalidConfigurationException::missing('profile');
        }

        return $this->profile->getServerKey();
    }

    /**
     * The profile ID the SDK was configured with, never the one echoed by the payload.
     *
     * @throws InvalidConfigurationException when no profile was set
     */
    final public function getConfiguredProfileId(): int
    {
        if (null === $this->profile) {
            throw InvalidConfigurationException::missing('profile');
        }

        return $this->profile->getProfileId();
    }
}
