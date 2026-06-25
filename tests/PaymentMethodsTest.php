<?php

declare(strict_types=1);

use Paytabs\Sdk\PaymentMethod\AbstractMethod;
use Paytabs\Sdk\PaymentMethod\Methods\ApplePay;
use Paytabs\Sdk\PaymentMethod\Methods\Card;
use Paytabs\Sdk\PaymentMethod\Methods\PayTabsAll;
use Paytabs\Sdk\PaymentMethod\Methods\Sadad;
use Paytabs\Sdk\PaymentMethod\MethodsFactory;
use Paytabs\Sdk\Request\Payload\Parts\PaymentMethods;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
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

    public function testFactoryConvenienceMethods(): void
    {
        $all = MethodsFactory::createPayTabsAllMethod();
        self::assertInstanceOf(AbstractMethod::class, $all);
        self::assertInstanceOf(PayTabsAll::class, $all);
        self::assertIsString($all::CODE);
        self::assertIsString($all::PT_CODE);
        self::assertIsArray($all::supportedCurrencies());

        $card = MethodsFactory::createCardMethod();
        self::assertInstanceOf(AbstractMethod::class, $card);
        self::assertInstanceOf(Card::class, $card);
        self::assertIsString($card::CODE);
        self::assertIsString($card::PT_CODE);
        self::assertIsArray($card::supportedCurrencies());

        $apple = MethodsFactory::createApplePayMethod();
        self::assertInstanceOf(AbstractMethod::class, $apple);
        self::assertInstanceOf(ApplePay::class, $apple);
        self::assertIsString($apple::CODE);
        self::assertIsString($apple::PT_CODE);
        self::assertIsArray($apple::supportedCurrencies());

        $sadad = MethodsFactory::createSadadMethod();
        self::assertInstanceOf(AbstractMethod::class, $sadad);
        self::assertInstanceOf(Sadad::class, $sadad);
        self::assertIsArray($sadad::supportedCurrencies());
    }

    public function testPaymentMethodsBuilderIncludeExclude(): void
    {
        $methods = PaymentMethods::init([MethodsFactory::createApplePayMethod(), 'card'])
            ->includeMethod(MethodsFactory::createCardMethod())
            ->includeMethods(['fawry', MethodsFactory::createSadadMethod()])
            ->excludeMethod('sadad')
            ->excludeMethod(MethodsFactory::createFawryMethod())
        ;

        self::assertIsObject($methods);

        $built = $methods->build();
        self::assertIsArray($built);
        self::assertNotEmpty($built);

        // Basic sanity checks: ensure excluded methods are not present by key or value when possible
        $flatten = json_encode($built);
        self::assertIsString($flatten);
        self::assertStringContainsString('-sadad', strtolower($flatten));
        // Ensure at least one included method is present
        self::assertStringContainsString('card', strtolower($flatten));
    }
}
