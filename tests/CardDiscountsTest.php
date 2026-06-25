<?php

declare(strict_types=1);

use Paytabs\Sdk\Enums\CardDiscountType;
use Paytabs\Sdk\Request\Payload\Parts\CardDiscounts;
use Paytabs\Sdk\Request\Payload\Parts\Partials\CardDiscount;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertEquals;

/**
 * @internal
 *
 * @coversNothing
 */
final class CardDiscountsTest extends TestCase
{
    public function testCardDiscounts(): void
    {
        $cardDiscounts = new CardDiscounts(
            new CardDiscount(
                CardDiscountType::Fixed,
                10.0,
                '4111',
                '10 Fixed Discount on Cards starting with 4111'
            ),
            new CardDiscount(
                CardDiscountType::Percent,
                5.0,
                '40000,5123',
                '5% Discount applied to Cards starting with 4000 or 5123'
            )
        );

        $apiBody = $cardDiscounts->build();

        assertArrayHasKey('card_discounts', $apiBody);

        $apiDiscounts = $apiBody['card_discounts'];

        assertEquals(2, count($apiDiscounts));

        $apiFixedDiscount = $apiDiscounts[0];
        $apiPercentDiscount = $apiDiscounts[1];

        assertArrayHasKey('discount_amount', $apiFixedDiscount);
        assertArrayHasKey('discount_percent', $apiPercentDiscount);

        $cardDiscounts->includeDiscount(
            new CardDiscount(
                CardDiscountType::Fixed,
                15.0,
                '4111,40000',
                '15 Fixed Discount on Cards starting with 4111 or 40000'
            )
        );

        $apiBody = $cardDiscounts->build();

        assertEquals(3, count($apiBody['card_discounts']));

        assertArrayHasKey('discount_amount', $apiBody['card_discounts'][2]);
    }

    public function testInvalidPattern(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $discountInvalid = new CardDiscount(
            CardDiscountType::Percent,
            5.0,
            '4,A1',
            '5% Discount applied to Cards starting with 4000 or 5123'
        );
    }
}
