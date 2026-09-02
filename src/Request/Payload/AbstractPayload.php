<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Payload;

use Paytabs\Sdk\Enums\HttpRequestPart;

abstract class AbstractPayload implements PayloadInterface
{
    protected array $headers = [];
    protected array $body = [];
    protected array $query = [];
    protected array $path = [];

    public function buildHeader(array|PartInterface $part): void
    {
        $this->buildPart($part, HttpRequestPart::Header);
    }

    public function buildBody(array|PartInterface $part, bool $merge = false): void
    {
        $this->buildPart($part, HttpRequestPart::Body, $merge);
    }

    public function buildQuery(array|PartInterface $part): void
    {
        $this->buildPart($part, HttpRequestPart::Query);
    }

    public function buildPath(array|PartInterface $part): void
    {
        $this->buildPart($part, HttpRequestPart::Path);
    }

    public function getBody(bool $removeNulls = true): array
    {
        return $this->get($this->body, $removeNulls);
    }

    public function getQuery(bool $removeNulls = true): array
    {
        return $this->get($this->query, $removeNulls);
    }

    public function getPath(bool $removeNulls = true): array
    {
        return $this->get($this->path, $removeNulls);
    }

    public function getHeaders(bool $removeNulls = true): array
    {
        return $this->get($this->headers, $removeNulls);
    }

    public function exists(string $key, ?HttpRequestPart $httpPart = null): bool
    {
        if (null === $httpPart) {
            return \array_key_exists($key, $this->headers)
                || \array_key_exists($key, $this->body)
                || \array_key_exists($key, $this->query)
                || \array_key_exists($key, $this->path);
        }

        // match is exhaustive over the enum, so an unreachable default throw
        // is not needed: a new case becomes a TypeError at the call site.
        return match ($httpPart) {
            HttpRequestPart::Header => \array_key_exists($key, $this->headers),
            HttpRequestPart::Body => \array_key_exists($key, $this->body),
            HttpRequestPart::Query => \array_key_exists($key, $this->query),
            HttpRequestPart::Path => \array_key_exists($key, $this->path),
        };
    }

    private function buildPart(array|PartInterface $part, HttpRequestPart $httpPart, bool $merge = false): void
    {
        $newPart = ($part instanceof PartInterface)
            ? $part->build()
            : $part;

        match ($httpPart) {
            HttpRequestPart::Header => $this->add($this->headers, $newPart),
            HttpRequestPart::Body => $this->add($this->body, $newPart, $merge),
            HttpRequestPart::Query => $this->add($this->query, $newPart),
            HttpRequestPart::Path => $this->add($this->path, $newPart),
        };
    }

    private function add(array &$array, array $newItems, bool $merge = false): void
    {
        if ($merge) {
            $array = array_merge_recursive($array, $newItems);
        } else {
            $array = array_merge($array, $newItems);
        }
    }

    private function get(array $array, bool $removeNulls): array
    {
        if ($removeNulls) {
            return $this->filterNulls($array);
        }

        return $array;
    }

    private function filterNulls(array $array): array
    {
        foreach ($array as $key => $value) {
            if (\is_array($value)) {
                $filtered = $this->filterNulls($value);

                // Test the *filtered* result, not the original.
                // Emit the empty arrays those might come up after filtering.
                if ([] === $filtered) {
                    unset($array[$key]);
                } else {
                    $array[$key] = $filtered;
                }

                continue;
            }

            if (null === $value || '' === $value) {
                unset($array[$key]);
            }
        }

        return $array;
    }
}
