<?php

namespace Paytabs\Sdk\Holder;

interface PayloadInterface
{
    public function buildHeader(PartInterface|array $part): void;

    /** @param bool $merge Merge the values, otherwise the new value will override any existing value */
    public function buildBody(PartInterface|array $part, bool $merge = false): void;

    public function buildQuery(PartInterface|array $part): void;

    public function buildPath(PartInterface|array $part): void;

    //

    public function getHeaders(bool $removeNulls = true): array;

    public function getBody(bool $removeNulls = true): array;

    public function getQuery(bool $removeNulls = true): array;

    public function getPath(bool $removeNulls = true): array;
}
