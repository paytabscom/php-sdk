<?php

namespace Paytabs\Sdk\Holder\Parts\Invoice;

use Paytabs\Sdk\Holder\PartInterface;

class LineItem implements PartInterface
{
    public ?string $sku = null;
    public ?string $description = null;
    public ?string $url = null;

    public ?int $quantity = null;
    public ?float $unitCost = null;
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

    public function setPrice(int $quantity, float $unitCost, float $netTotal): self
    {
        $this->quantity = $quantity;
        $this->unitCost = $unitCost;
        $this->netTotal = $netTotal;

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
