<?php

namespace Paytabs\Sdk\Response\Responses;

use Paytabs\Sdk\Response\Payloads\Callbacks\Browser;

class BrowserReturn extends AbstractResponse
{
    protected array $postArray;

    //

    public function __construct(string $response, array $postArray)
    {
        parent::__construct($response);

        $this->postArray = $postArray;
    }

    public function getResponse(): Browser
    {
        $browser = new Browser();
        $browser->fromJson($this->getJson());

        return $browser;
    }

    //

    final public function isValid(): bool
    {
        $post_values = $this->postArray;
        if (empty($post_values) || !array_key_exists('signature', $post_values)) {
            return false;
        }

        $serverKey = $this->gateway->getServerKey();

        // Request body include a signature post Form URL encoded field
        // 'signature' (hexadecimal encoding for hmac of sorted post form fields)
        $requestSignature = $post_values["signature"];
        unset($post_values["signature"]);
        $fields = array_filter($post_values);

        // Sort form fields
        ksort($fields);

        // Generate URL-encoded query string of Post fields except signature field.
        $query = http_build_query($fields);

        return $this->isGenuine($query, $requestSignature, $serverKey);
    }
}
