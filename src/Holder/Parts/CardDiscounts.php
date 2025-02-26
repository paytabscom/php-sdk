<?php

namespace Paytabs\Sdk\Holder\Parts;

use Paytabs\Sdk\Holder\Parts\Partials\CardDiscount;

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
                fn (CardDiscount $discount) => $discount->build(),
                $this->discounts
            ),
        ];
    }
}
