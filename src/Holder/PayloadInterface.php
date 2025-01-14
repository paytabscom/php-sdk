<?php

namespace Paytabs\Sdk\Holder;

interface PayloadInterface
{
    public function buildHeader(PartInterface|array $part): void;

    public function buildBody(PartInterface|array $part): void;

    public function buildQuery(PartInterface|array $part): void;

    //

    public function getHeaders(bool $removeNulls = true): array;

    public function getBody(bool $removeNulls = true): array;

    public function getQuery(bool $removeNulls = true): array;
}
