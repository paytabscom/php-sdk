<?php

namespace Paytabs\Sdk\Holder\Payload;

use Paytabs\Sdk\Enums\HttpRequestPart;
use Exception;
use Paytabs\Sdk\Holder\PartInterface;
use Paytabs\Sdk\Holder\PayloadInterface;

abstract class AbstractPayload implements PayloadInterface
{
    protected array $headers = [];
    protected array $body = [];
    protected array $query = [];

    //

    public function buildHeader(PartInterface|array $part): void
    {
        $this->buildPart($part, HttpRequestPart::Header);
    }

    public function buildBody(PartInterface|array $part): void
    {
        $this->buildPart($part, HttpRequestPart::Body);
    }

    public function buildQuery(PartInterface|array $part): void
    {
        $this->buildPart($part, HttpRequestPart::Query);
    }

    public function getBody(bool $removeNulls = true): array
    {
        return $this->get($this->body, $removeNulls);
    }

    public function getQuery(bool $removeNulls = true): array
    {
        return $this->get($this->query, $removeNulls);
    }

    public function getHeaders(bool $removeNulls = true): array
    {
        return $this->get($this->headers, $removeNulls);
    }

    //

    private function buildPart(PartInterface|array $part, HttpRequestPart $httpPart): void
    {
        $newPart = ($part instanceof PartInterface)
            ? $part->build()
            : $part;

        switch ($httpPart) {
            case HttpRequestPart::Header:
                $this->add($this->headers, $newPart);

                break;
            case HttpRequestPart::Body:
                $this->add($this->body, $newPart);

                break;
            case HttpRequestPart::Query:
                $this->add($this->query, $newPart);

                break;

            default:
                throw new Exception('Not implemented');
                break;
        }
    }

    private function add(array &$array, array $newItems): void
    {
        $array += $newItems;
    }

    private function get(array $array, bool $removeNulls): array
    {
        if ($removeNulls) {
            return $this->filterNulls($array);
        }

        return $array;
    }

    //

    private function filterNulls(array $array): array
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->filterNulls($value);
            }
            if (is_null($value) || $value == '') {
                unset($array[$key]);
            }
        }
        return $array;
    }
}
