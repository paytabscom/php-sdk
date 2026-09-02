<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Responses\Webhook\TransactionResult;

use Paytabs\Sdk\Enums\TranStatus;
use Paytabs\Sdk\Helpers\Helpers;
use Paytabs\Sdk\Response\Payload\Payloads\Callbacks\Ipn;
use Paytabs\Sdk\Response\Responses\Webhook\AbstractTransactionResult;

class Callback extends AbstractTransactionResult
{
    protected array $postArray;

    public function __construct(?string $response, array $headers = [], array $localParams = [])
    {
        $this->payload = new Ipn();

        parent::__construct($response, $headers, $localParams);
    }

    public static function init(): self
    {
        $response_stream = file_get_contents('php://input');

        if (false === $response_stream || '' === $response_stream) {
            throw new \InvalidArgumentException('Invalid IPN payload: empty request body');
        }

        $headers = getallheaders() ?: [];

        return self::initWith($response_stream, $headers);
    }

    public static function initWith(string $jsonPayload, array $headers): self
    {
        // Guard here as well as in init(): an empty or non-JSON body otherwise
        // surfaced as a bare \JsonException raised from a constructor, deep in
        // the payload layer.
        if ('' === trim($jsonPayload)) {
            throw new \InvalidArgumentException('Invalid IPN payload: empty request body');
        }

        if (!Helpers::jsonValidate($jsonPayload)) {
            throw new \InvalidArgumentException('Invalid IPN payload: body is not valid JSON');
        }

        // Lower case all keys
        $headers = array_change_key_case($headers);

        return new self($jsonPayload, $headers);
    }

    /**
     * The profile ID reported by the IPN payload.
     *
     * Only trustworthy once isGenuine() has returned true. Use
     * getConfiguredProfileId() for the locally configured value.
     */
    public function getProfileId(): int
    {
        return self::required($this->getCallbackPayload()->profile_id, 'profile_id');
    }

    public function getTranRef(): string
    {
        return self::required($this->getCallbackPayload()->tran_ref, 'tran_ref');
    }

    public function getCartId(): string
    {
        return self::required($this->getCallbackPayload()->cart_id, 'cart_id');
    }

    public function getTranStatus(): TranStatus
    {
        $paymentResult = self::required($this->getCallbackPayload()->payment_result, 'payment_result');

        return self::required($paymentResult->tranStatus, 'payment_result.response_status');
    }

    protected function isValid(): bool
    {
        if (!\array_key_exists('signature', $this->headers)) {
            return false;
        }

        // is_string(), not !empty(): see AbstractBrowser::isValid().
        return \is_string($this->headers['signature']) && '' !== $this->headers['signature'];
    }

    protected function isSameProfile(): bool
    {
        // Compare against the configured credential, not the payload's own field.
        return $this->getCallbackPayload()->profile_id === $this->getConfiguredProfileId();
    }

    protected function prepareHashablePayload(): string
    {
        return $this->payload->getResponseData();
    }

    protected function getServerSignature(): string
    {
        $signature = $this->headers['signature'] ?? '';

        return \is_string($signature) ? $signature : '';
    }

    private function getCallbackPayload(): Ipn
    {
        if (!$this->payload instanceof Ipn) {
            throw new \LogicException('Callback payload is not initialized.');
        }

        return $this->payload;
    }
}
