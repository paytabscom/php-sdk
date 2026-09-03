<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Responses\Webhook\TransactionResult;

use Paytabs\Sdk\Enums\TranStatus;
use Paytabs\Sdk\Response\Payload\Payloads\Callbacks\Browser;
use Paytabs\Sdk\Response\Responses\Webhook\AbstractTransactionResult;

abstract class AbstractBrowser extends AbstractTransactionResult
{
    protected array $requestArray;

    public function __construct(string $response, array $requestArray, array $localParams)
    {
        $this->payload = new Browser();

        parent::__construct($response, [], $localParams);

        $this->requestArray = $requestArray;
    }

    public static function initWith(array $requestArray, array $localParams = []): static
    {
        if (!$requestArray) {
            throw new \InvalidArgumentException('Invalid browser callback payload: empty request data');
        }

        $dataJson = json_encode($requestArray);

        if (false === $dataJson) {
            throw new \InvalidArgumentException('Invalid browser callback payload: not encodable as JSON');
        }

        return new static($dataJson, $requestArray, $localParams);
    }

    public function getTranRef(): string
    {
        return self::required($this->getBrowserPayload()->tranRef, 'tranRef');
    }

    public function getCartId(): string
    {
        return self::required($this->getBrowserPayload()->cartId, 'cartId');
    }

    public function getTranStatus(): TranStatus
    {
        return self::required($this->getBrowserPayload()->tranStatus, 'respStatus');
    }

    protected function isValid(): bool
    {
        $requestValues = $this->requestArray;
        if (empty($requestValues) || !\array_key_exists('signature', $requestValues)) {
            return false;
        }

        return \is_string($requestValues['signature']) && '' !== $requestValues['signature'];
    }

    /**
     * Browser callbacks carry no profile_id field, so there is nothing to compare.
     * Profile binding still holds: the HMAC is keyed on the configured server key.
     */
    protected function isSameProfile(): bool
    {
        return true;
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
            static fn($value): bool => null !== $value && '' !== $value
        );

        // Sort form fields.
        ksort($fields);

        // Generate URL-encoded query string of fields except signature field.
        return http_build_query($fields);
    }

    protected function getServerSignature(): string
    {
        $signature = $this->requestArray['signature'] ?? '';

        return \is_string($signature) ? $signature : '';
    }

    private function getBrowserPayload(): Browser
    {
        if (!$this->payload instanceof Browser) {
            throw new \LogicException('Browser payload is not initialized.');
        }

        return $this->payload;
    }
}
