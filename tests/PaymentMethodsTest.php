<?php

declare(strict_types=1);

use Paytabs\Sdk\PaymentMethod\AbstractMethod;
use Paytabs\Sdk\PaymentMethod\Methods\Card;
use Paytabs\Sdk\PaymentMethod\Methods\Sadad;
use Paytabs\Sdk\PaymentMethod\MethodsFactory;
use PHPUnit\Framework\TestCase;

final class PaymentMethodsTest extends TestCase
{
    public function testCreatePaymentMethod(): void
    {
        $card = new Card();
        self::assertTrue($card::ACTIVE);

        $sadad = new Sadad();
        self::assertContains('SAR', $sadad::supportedCurrencies());
        self::assertContains('SAR', Sadad::supportedCurrencies());
    }

    public function testCreatePaymentMethods(): void
    {
        $codes = ['card', 'creditcard', 'sadad', 'applePay', 'apple_pay'];
        foreach ($codes as $code) {
            $method = MethodsFactory::createMethod($code);
            self::assertInstanceOf(AbstractMethod::class, $method);
        }
    }

    public function testCreatePaymentMethodsInvalid(): void
    {
        $invalidCodes = ['test', 'test2'];
        foreach ($invalidCodes as $code) {
            $this->expectException(Exception::class);
            $method = MethodsFactory::createMethod($code);
            self::assertNull($method);
        }
    }

    //

    public function testCreatePaymentMethodsById(): void
    {
        $ids = [1, 10, 50];
        foreach ($ids as $id) {
            $method = MethodsFactory::createMethodById($id);
            self::assertInstanceOf(AbstractMethod::class, $method);
        }
    }

    public function testCreatePaymentMethodsByIdInvalid(): void
    {
        $ids = [333, 444];
        foreach ($ids as $id) {
            $this->expectException(Exception::class);
            $method = MethodsFactory::createMethodById($id);
            self::assertNull($method);
        }
    }

    public function testCreatePaymentMethodsByUnique(): void
    {
        $codes = ['paytabs_card', 'paytabs_sadad'];
        foreach ($codes as $code) {
            $method = MethodsFactory::createMethodByUnique($code);
            self::assertInstanceOf(AbstractMethod::class, $method);
        }
    }

    public function testCreatePaymentMethodsByUniqueInvalid(): void
    {
        $invalidCodes = ['test', 'test2'];
        foreach ($invalidCodes as $code) {
            $this->expectException(Exception::class);
            $method = MethodsFactory::createMethodByUnique($code);
            self::assertNull($method);
        }
    }
}
