<?php

namespace Paytabs\Sdk\Response\Responses\Webhook\TransactionResult;

use Exception;
use Paytabs\Sdk\Response\Payloads\Callbacks\Browser;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult;

class BrowserReturn extends TransactionResult
{
    protected array $postArray;

    //

    public static function init(array $localParams = []): self
    {
        $data = filter_input_array(INPUT_POST);

        return self::initWith($data, $localParams);
    }

    public static function initWith(array $postArray, array $localParams = []): self
    {
        if (!$postArray) {
            throw new Exception('Invalid init');
        }

        $dataJson = json_encode($postArray);

        return new self($dataJson, $postArray, $localParams);
    }

    public function __construct(string $response, array $postArray, array $localParams)
    {
        $this->payload = new Browser;

        parent::__construct($response, [], $localParams);

        $this->postArray = $postArray;
    }

    //

    protected function isValid(): bool
    {
        $post_values = $this->postArray;
        if (empty($post_values) || !\array_key_exists('signature', $post_values)) {
            return false;
        }

        return !empty($post_values['signature']);
    }

    protected function prepareHashablePayload(): string
    {
        $post_values = $this->postArray;

        // Request body include a signature post Form URL encoded field
        // 'signature' (hexadecimal encoding for hmac of sorted post form fields)
        unset($post_values['signature']);

        // Remove any local query param sent within the generate payment page request
        foreach ($this->localParams as $localParam) {
            unset($post_values[$localParam]);
        }

        // Remove any empty field
        $fields = array_filter($post_values);

        // Sort form fields
        ksort($fields);

        // Generate URL-encoded query string of Post fields except signature field.
        return http_build_query($fields);
    }

    protected function getServerSignature(): string
    {
        $post_values = $this->postArray;

        return $post_values['signature'];
    }
}
