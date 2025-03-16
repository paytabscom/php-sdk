<?php

namespace Paytabs\Sdk\Response\Payload\Parts;

class Invoice
{
    public int $id;

    public float $shipping_charges;
    public float $extra_charges;
    public float $extra_discount;

    public float $total;

    public string $activation_date;
    public string $expiry_date;
    public string $due_date;
    public string $issue_date;

    /** @var LineItem[] */
    public array $line_items;
}
