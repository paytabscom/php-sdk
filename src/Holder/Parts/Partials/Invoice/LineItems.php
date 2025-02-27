<?php

namespace Paytabs\Sdk\Holder\Parts\Partials\Invoice;

use Paytabs\Sdk\Holder\PartInterface;

class LineItems implements PartInterface
{
    /** @var LineItem[] */
    public array $lineItems;

    public static function init(): static
    {
        return new static();
    }

    public function addLineItem(LineItem $lineItem): self
    {
        $this->lineItems[] = $lineItem;

        return $this;
    }

    public function build(): array
    {
        $items['line_items'] = [];

        foreach ($this->lineItems as $item) {
            $items['line_items'][] = $item->build();
        }

        return $items;
    }
}
