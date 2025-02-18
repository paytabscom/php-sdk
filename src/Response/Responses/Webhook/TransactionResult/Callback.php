<?php

namespace Paytabs\Sdk\Response\Responses\Webhook\TransactionResult;

use Paytabs\Sdk\Response\Payloads\Callbacks\Ipn;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult;

class Callback extends TransactionResult
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
}
