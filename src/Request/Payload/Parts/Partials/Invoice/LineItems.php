<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Payload\Parts\Partials\Invoice;

use Paytabs\Sdk\Request\Payload\PartInterface;

class LineItems implements PartInterface
{
    /** @var LineItem[] */
    public array $lineItems;

    public function __construct(LineItem ...$lineItems)
    {
        $this->lineItems = $lineItems;
    }

    public function addLineItem(LineItem $lineItem): self
    {
        $this->lineItems[] = $lineItem;

        return $this;
    }

    public function build(): array
    {
        if (empty($this->lineItems)) {
            throw new \InvalidArgumentException('At least one line item is required for the LineItems object.');
        }

        $items['line_items'] = [];

        foreach ($this->lineItems as $item) {
            $items['line_items'][] = $item->build();
        }

        return $items;
    }
}
