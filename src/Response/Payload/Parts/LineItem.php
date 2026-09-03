<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Payload\Parts;

class LineItem
{
    public ?string $sku = null;
    public ?string $description = null;
    public ?string $url = null;

    public ?float $unit_cost = null;
    public ?int $quantity = null;
    public ?float $net_total = null;
    public ?float $discount_rate = null;
    public ?float $discount_amount = null;
    public ?float $tax_rate = null;
    public ?float $tax_total = null;
    public ?float $total = null;
}
