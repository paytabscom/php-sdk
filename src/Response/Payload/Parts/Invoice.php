<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Payload\Parts;

class Invoice
{
    public ?int $id = null;

    public ?float $shipping_charges = null;
    public ?float $extra_charges = null;
    public ?float $extra_discount = null;

    public ?float $total = null;

    public ?string $activation_date = null;
    public ?string $expiry_date = null;
    public ?string $due_date = null;
    public ?string $issue_date = null;

    /** @var LineItem[] */
    public array $line_items = [];
}
