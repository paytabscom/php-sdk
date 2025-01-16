<?php

declare(strict_types=1);

use Paytabs\Sdk\PaymentMethod\Methods\Card;
use PHPUnit\Framework\TestCase;

final class PaymentMethodsTest extends TestCase
{
    public function testCreatePaymentMethod(): void
    {
        $card = new Card();
        $this::assertTrue($card::ACTIVE);
    }
}
