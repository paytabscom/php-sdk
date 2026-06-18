<?php

namespace Paytabs\Sdk\Response\Responses\Webhook\TransactionResult;

use Paytabs\Sdk\Response\Payload\Payloads\Callbacks\Browser as BrowserPayload;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult;

abstract class Browser extends TransactionResult
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
            throw new \Exception('Invalid init');
        }

        $dataJson = json_encode($requestArray);

        return new static($dataJson, $requestArray, $localParams);
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

        // Remove any empty field.
        $fields = array_filter($requestValues);

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
}
