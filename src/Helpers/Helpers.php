<?php

namespace Paytabs\Sdk\Helpers;

use Paytabs\Sdk\Enums\ResponseStage;

class Helpers
{
    public static function urlBuild(
        string $base_url,
        string $path,
        bool $remove_trailing_slash = false
    ): string {
        $base = rtrim($base_url, '/');

        $path = ltrim($path ?? '', '/');

        $url = $base.'/'.$path;

        if ($remove_trailing_slash) {
            $url = rtrim($url, '/');
        }

        return $url;
    }

    public static function responseStage($json): ResponseStage
    {
        // "Delete Token" request returns same structure but code=0
        if (isset($json->code) && $json->code > 0) {
            return ResponseStage::Error;
        }

        if (
            isset($json->tran_ref, $json->redirect_url)
            && !empty($json->redirect_url)
        ) {
            return ResponseStage::Redirect;
        }

        if (isset($json->payment_result)) {
            return ResponseStage::Completed;
        }

        return ResponseStage::Unknown;
    }

    public static function jsonValidate($json): bool
    {
        return json_validate($json);
    }
}

// PHP < 8.3
if (!\function_exists('json_validate')) {
    /**
     * Validates a JSON string.
     *
     * @param string $json  the JSON string to validate
     * @param int    $depth Maximum depth. Must be greater than zero.
     * @param int    $flags bitmask of JSON decode options
     *
     * @return bool returns true if the string is a valid JSON, otherwise false
     */
    function json_validate($json, $depth = 512, $flags = 0)
    {
        if (!\is_string($json)) {
            return false;
        }

        try {
            json_decode($json, false, $depth, $flags | JSON_THROW_ON_ERROR);

            return true;
        } catch (\JsonException $e) {
            return false;
        }
    }
}
