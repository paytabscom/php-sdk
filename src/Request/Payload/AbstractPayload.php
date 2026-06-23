<?php

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

    public function buildBody(array|PartInterface $part, bool $merge = true): void
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

    public function exists(string $key, ?HttpRequestPart $httpPart): bool
    {
        if ($httpPart === null) {
            return \array_key_exists($key, $this->headers)
                || \array_key_exists($key, $this->body)
                || \array_key_exists($key, $this->query)
                || \array_key_exists($key, $this->path);
        }

        switch ($httpPart) {
            case HttpRequestPart::Header:
                return \array_key_exists($key, $this->headers);

            case HttpRequestPart::Body:
                return \array_key_exists($key, $this->body);

            case HttpRequestPart::Query:
                return \array_key_exists($key, $this->query);

            case HttpRequestPart::Path:
                return \array_key_exists($key, $this->path);

            default:
                throw new \Exception('Not implemented');
        }
    }

    private function buildPart(array|PartInterface $part, HttpRequestPart $httpPart, bool $merge = false): void
    {
        $newPart = ($part instanceof PartInterface)
            ? $part->build()
            : $part;

        switch ($httpPart) {
            case HttpRequestPart::Header:
                $this->add($this->headers, $newPart);

                break;

            case HttpRequestPart::Body:
                $this->add($this->body, $newPart, $merge);

                break;

            case HttpRequestPart::Query:
                $this->add($this->query, $newPart);

                break;

            case HttpRequestPart::Path:
                $this->add($this->path, $newPart);

                break;

            default:
                throw new \Exception('Not implemented');

                break;
        }
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
                $array[$key] = $this->filterNulls($value);
            }
            if (null === $value || '' === $value) {
                unset($array[$key]);
            }
        }

        return $array;
    }
}
