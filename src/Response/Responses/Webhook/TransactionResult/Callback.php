<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Responses\Webhook\TransactionResult;

use Paytabs\Sdk\Enums\TranStatus;
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
        $headers = getallheaders();

        return self::initWith($response_stream, $headers);
    }

    public static function initWith(string $jsonPayload, array $headers): self
    {
        // Lower case all keys
        $headers = array_change_key_case($headers);

        return new self($jsonPayload, $headers);
    }

    public function getProfileId(): int
    {
        return $this->getCallbackPayload()->profile_id;
    }

    public function getTranRef(): string
    {
        return $this->getCallbackPayload()->tran_ref;
    }

    public function getCartId(): string
    {
        return $this->getCallbackPayload()->cart_id;
    }

    public function getTranStatus(): TranStatus
    {
        return $this->getCallbackPayload()->payment_result->tranStatus;
    }

    protected function isValid(): bool
    {
        if (!\array_key_exists('signature', $this->headers)) {
            return false;
        }

        return !empty($this->headers['signature']);
    }

    protected function prepareHashablePayload(): string
    {
        return $this->payload->getResponseData();
    }

    protected function getServerSignature(): string
    {
        return $this->headers['signature'];
    }

    private function getCallbackPayload(): Ipn
    {
        if (!$this->payload instanceof Ipn) {
            throw new \LogicException('Callback payload is not initialized.');
        }

        return $this->payload;
    }
}
