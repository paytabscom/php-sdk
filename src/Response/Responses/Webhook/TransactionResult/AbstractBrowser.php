<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Responses\Webhook\TransactionResult;

use Paytabs\Sdk\Enums\TranStatus;
use Paytabs\Sdk\Response\Payload\Payloads\Callbacks\Browser as BrowserPayload;
use Paytabs\Sdk\Response\Responses\Webhook\AbstractTransactionResult;

abstract class AbstractBrowser extends AbstractTransactionResult
{
    protected array $requestArray;

    public function __construct(string $response, array $requestArray, array $localParams)
    {
        $this->payload = new BrowserPayload();

        parent::__construct($response, [], $localParams);

        $this->requestArray = $requestArray;
    }

    public static function initWith(array $requestArray, array $localParams = []): static
    {
        if (!$requestArray) {
            throw new \InvalidArgumentException('Invalid browser callback payload: empty request data');
        }

        $dataJson = json_encode($requestArray);

        return new static($dataJson, $requestArray, $localParams);
    }

    public function getTranRef(): string
    {
        return $this->getBrowserPayload()->tranRef;
    }

    public function getCartId(): string
    {
        return $this->getBrowserPayload()->cartId;
    }

    public function getTranStatus(): TranStatus
    {
        return $this->getBrowserPayload()->tranStatus;
    }

    protected function isValid(): bool
    {
        $requestValues = $this->requestArray;
        if (empty($requestValues) || !\array_key_exists('signature', $requestValues)) {
            return false;
        }

        return !empty($requestValues['signature']);
    }

    protected function prepareHashablePayload(): string
    {
        $requestValues = $this->requestArray;

        // Request body includes a signature field in form/url-encoded browser callbacks.
        unset($requestValues['signature']);

        // Remove any local query param sent within the generated payment page request.
        foreach ($this->localParams as $localParam) {
            unset($requestValues[$localParam]);
        }

        // Remove null/empty-string fields only; preserve values like "0" for signature stability.
        $fields = array_filter(
            $requestValues,
            static fn ($value): bool => null !== $value && '' !== $value
        );

        // Sort form fields.
        ksort($fields);

        // Generate URL-encoded query string of fields except signature field.
        return http_build_query($fields);
    }

    protected function getServerSignature(): string
    {
        $requestValues = $this->requestArray;

        return $requestValues['signature'];
    }

    private function getBrowserPayload(): BrowserPayload
    {
        if (!$this->payload instanceof BrowserPayload) {
            throw new \LogicException('Browser payload is not initialized.');
        }

        return $this->payload;
    }
}
