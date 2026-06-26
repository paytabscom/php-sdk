<?php

declare(strict_types=1);

use Paytabs\Sdk\PaymentMethod\AbstractMethod;
use Paytabs\Sdk\PaymentMethod\Methods\Amex;
use Paytabs\Sdk\PaymentMethod\Methods\ApplePay;
use Paytabs\Sdk\PaymentMethod\Methods\Card;
use Paytabs\Sdk\PaymentMethod\Methods\PayTabsAll;
use Paytabs\Sdk\PaymentMethod\Methods\Sadad;
use Paytabs\Sdk\PaymentMethod\PaymentMethodsFactory;
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
        $codes = ['card', 'creditcard', 'sadad', 'applePay', 'apple'];
        foreach ($codes as $code) {
            $method = PaymentMethodsFactory::createMethod($code);
            self::assertInstanceOf(AbstractMethod::class, $method);
        }
    }

    public function testCreatePaymentMethodsInvalid(): void
    {
        $invalidCodes = ['test', 'test2'];
        foreach ($invalidCodes as $code) {
            $this->expectException(Exception::class);
            $method = PaymentMethodsFactory::createMethod($code);
            self::assertNull($method);
        }
    }

    public function testCreatePaymentMethodsById(): void
    {
        $ids = [ApplePay::ID, PayTabsAll::ID, Amex::ID];
        foreach ($ids as $id) {
            $method = PaymentMethodsFactory::createMethodById($id);
            self::assertInstanceOf(AbstractMethod::class, $method);
        }
    }

    public function testCreatePaymentMethodsByIdInvalid(): void
    {
        $ids = [9333, 9444];
        foreach ($ids as $id) {
            $this->expectException(Exception::class);
            $method = PaymentMethodsFactory::createMethodById($id);
            self::assertNull($method);
        }
    }

    public function testCreatePaymentMethodsByUnique(): void
    {
        $codes = ['paytabs_card', 'paytabs_sadad'];
        foreach ($codes as $code) {
            $method = PaymentMethodsFactory::createMethodByUnique($code);
            self::assertInstanceOf(AbstractMethod::class, $method);
        }
    }

    public function testCreatePaymentMethodsByUniqueInvalid(): void
    {
        $invalidCodes = ['test', 'test2'];
        foreach ($invalidCodes as $code) {
            $this->expectException(Exception::class);
            $method = PaymentMethodsFactory::createMethodByUnique($code);
            self::assertNull($method);
        }
    }

    public function testFactoryConvenienceMethods(): void
    {
        $all = PaymentMethodsFactory::createPayTabsAllMethod();
        self::assertInstanceOf(AbstractMethod::class, $all);
        self::assertInstanceOf(PayTabsAll::class, $all);
        self::assertIsString($all::CODE);
        self::assertIsString($all::PT_CODE);
        self::assertIsArray($all::supportedCurrencies());

        $card = PaymentMethodsFactory::createCardMethod();
        self::assertInstanceOf(AbstractMethod::class, $card);
        self::assertInstanceOf(Card::class, $card);
        self::assertIsString($card::CODE);
        self::assertIsString($card::PT_CODE);
        self::assertIsArray($card::supportedCurrencies());

        $apple = PaymentMethodsFactory::createApplePayMethod();
        self::assertInstanceOf(AbstractMethod::class, $apple);
        self::assertInstanceOf(ApplePay::class, $apple);
        self::assertIsString($apple::CODE);
        self::assertIsString($apple::PT_CODE);
        self::assertIsArray($apple::supportedCurrencies());

        $sadad = PaymentMethodsFactory::createSadadMethod();
        self::assertInstanceOf(AbstractMethod::class, $sadad);
        self::assertInstanceOf(Sadad::class, $sadad);
        self::assertIsArray($sadad::supportedCurrencies());
    }

    public function testPaymentMethodsBuilderIncludeExclude(): void
    {
        $methods = PaymentMethods::init([PaymentMethodsFactory::createApplePayMethod(), 'card'])
            ->includeMethod(PaymentMethodsFactory::createCardMethod())
            ->includeMethods(['fawry', PaymentMethodsFactory::createSadadMethod()])
            ->excludeMethod('sadad')
            ->excludeMethod(PaymentMethodsFactory::createFawryMethod())
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
