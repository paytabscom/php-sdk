<?php

namespace Paytabs\Sdk\Holder\Parts\Partials;

use Paytabs\Sdk\Enums\CardDiscountType;
use Paytabs\Sdk\Holder\PartInterface;

class CardDiscount implements PartInterface
{
    private CardDiscountType $discountType;
    private float $discountAmount;
    private string $cardsPatterns;
    private string $discountTitle;

    public function __construct(
        CardDiscountType $discountType,
        float $discountAmount,
        string $cardsPatterns,
        string $discountTitle
    ) {
        $this->discountType = $discountType;
        $this->discountAmount = $discountAmount;
        $this->cardsPatterns = $cardsPatterns;
        $this->discountTitle = $discountTitle;
    }

    public function build(): array
    {
        $discountKey = ($this->discountType === CardDiscountType::Fixed)
            ? 'discount_amount'
            : 'discount_percent';

        return [
            'discount_title' => $this->discountTitle,
            'discount_cards' => $this->cardsPatterns,
            $discountKey => $this->discountAmount,
        ];
    }
}
