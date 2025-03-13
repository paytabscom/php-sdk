<?php

namespace Paytabs\Sdk\Response\Payload\Parts;

class LineItem
{
    public string $sku;
    public string $description;
    public string $url;

    public float $unit_cost;
    public int $quantity;
    public float $net_total;
    public float $discount_rate;
    public float $discount_amount;
    public float $tax_rate;
    public float $tax_total;
    public float $total;
}
