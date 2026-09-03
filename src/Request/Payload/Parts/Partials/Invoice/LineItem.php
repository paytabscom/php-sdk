<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Payload\Parts\Partials\Invoice;

use InvalidArgumentException;
use Paytabs\Sdk\Request\Payload\PartInterface;

class LineItem implements PartInterface
{
    public ?string $sku = null;
    public ?string $description = null;
    public ?string $url = null;

    // Required fields for the PayTabs invoice API
    public int $quantity;
    public float $unitCost;

    public ?float $netTotal = null;

    public ?float $discountRate = null;
    public ?float $discountAmount = null;

    public ?float $taxRate = null;
    public ?float $taxTotal = null;

    public ?float $total = null;

    public static function init(): self
    {
        return new self();
    }

    public function setTitle(?string $sku, ?string $description = null, ?string $url = null)
    {
        $this->sku = $sku;
        $this->description = $description;
        $this->url = $url;

        return $this;
    }

    public function setPrice(int $quantity, float $unitCost, ?float $netTotal = null): self
    {
        $this->quantity = $quantity;
        $this->unitCost = $unitCost;

        if ($netTotal !== null) {
            $this->netTotal = $netTotal;
        }

        return $this;
    }

    public function setDiscount(float $rate, float $amount): self
    {
        $this->discountRate = $rate;
        $this->discountAmount = $amount;

        return $this;
    }

    public function setTax(float $rate, float $total): self
    {
        $this->taxRate = $rate;
        $this->taxTotal = $total;

        return $this;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function build(): array
    {
        if (!isset($this->quantity, $this->unitCost)) {
            throw new InvalidArgumentException('Quantity and unit cost are required for the LineItem object.');
        }

        return [
            'sku' => $this->sku,
            'description' => $this->description,
            'url' => $this->url,

            'quantity' => $this->quantity,
            'unit_cost' => $this->unitCost,
            'net_total' => $this->netTotal,

            'discount_rate' => $this->discountRate,
            'discount_amount' => $this->discountAmount,

            'tax_rate' => $this->taxRate,
            'tax_total' => $this->taxTotal,

            'total' => $this->total,
        ];
    }
}
