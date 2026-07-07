<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Payload\Parts;

use Paytabs\Sdk\Request\Payload\Parts\Partials\CardDiscount;

class CardDiscounts extends AbstractPart
{
    /** @var CardDiscount[] */
    private array $discounts;

    public function __construct(CardDiscount ...$discounts)
    {
        $this->discounts = $discounts;
    }

    public function includeDiscount(CardDiscount $discount): self
    {
        $this->discounts[] = $discount;

        return $this;
    }

    public function build(): array
    {
        return [
            'card_discounts' => array_map(
                static fn (CardDiscount $discount) => $discount->build(),
                $this->discounts
            ),
        ];
    }
}
