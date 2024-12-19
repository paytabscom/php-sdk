<?php

namespace Helpers;

class Helpers
{
    public static function urlBuild(
        string $base_url,
        string $path,
        bool $remove_trailing_slash = false
    ): string {
        $base = rtrim($base_url, '/');

        $path = ltrim($path ?? '', '/');

        $url = $base . '/' . $path;

        if ($remove_trailing_slash) {
            $url = rtrim($url, '/');
        }

        return $url;
    }
}
