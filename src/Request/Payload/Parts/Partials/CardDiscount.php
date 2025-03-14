<?php

namespace Paytabs\Sdk\Request\Payload\Parts\Partials;

use Paytabs\Sdk\Enums\CardDiscountType;
use Paytabs\Sdk\Request\Payload\PartInterface;

class CardDiscount implements PartInterface
{
    public const DISCOUNT_PATTERN_REGEX = '/^[0-9]{4,10}$/';

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

        if (!static::isValidDiscountPatterns($cardsPatterns)) {
            throw new \InvalidArgumentException('Invalid card discount patterns');
        }
    }

    public function build(): array
    {
        $discountKey = (CardDiscountType::Fixed === $this->discountType->value)
            ? 'discount_amount'
            : 'discount_percent';

        return [
            'discount_title' => $this->discountTitle,
            'discount_cards' => $this->cardsPatterns,
            $discountKey => $this->discountAmount,
        ];
    }

    public static function isValidDiscountPatterns(string $cardsPatterns)
    {
        $patterns = explode(',', $cardsPatterns);

        if (empty($patterns)) {
            return false;
        }

        foreach ($patterns as $prefix) {
            if (!static::isValidDiscountPattern($prefix)) {
                return false;
            }
        }

        return true;
    }

    public static function isValidDiscountPattern(string $pattern)
    {
        return preg_match(static::DISCOUNT_PATTERN_REGEX, $pattern);
    }
}
