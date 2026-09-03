<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Tests;

use Paytabs\Sdk\Enums\PaymentMethod;
use Paytabs\Sdk\PaymentMethod\AbstractMethod;
use Paytabs\Sdk\PaymentMethod\Methods\Amex;
use Paytabs\Sdk\PaymentMethod\Methods\ApplePay;
use Paytabs\Sdk\PaymentMethod\Methods\Card;
use Paytabs\Sdk\PaymentMethod\Methods\Halan;
use Paytabs\Sdk\PaymentMethod\Methods\PayTabsAll;
use Paytabs\Sdk\PaymentMethod\Methods\Sadad;
use Paytabs\Sdk\PaymentMethod\PaymentMethodsFactory;
use Paytabs\Sdk\Request\Payload\Parts\PaymentMethods;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
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

    /**
     * One case per invalid code: an `expectException()` inside a loop would
     * abort the test on the first iteration and leave the rest unasserted.
     */
    #[DataProvider('invalidCodeProvider')]
    public function testCreatePaymentMethodsInvalid(string $code): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Payment Method not found for Code: {$code}");

        PaymentMethodsFactory::createMethod($code);
    }

    public function testCreatePaymentMethodsById(): void
    {
        $ids = [ApplePay::ID, PayTabsAll::ID, Amex::ID];
        foreach ($ids as $id) {
            $method = PaymentMethodsFactory::createMethodById($id);
            self::assertInstanceOf(AbstractMethod::class, $method);
        }
    }

    #[DataProvider('invalidIdProvider')]
    public function testCreatePaymentMethodsByIdInvalid(int $id): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Payment Method not found for ID: {$id}");

        PaymentMethodsFactory::createMethodById($id);
    }

    /**
     * @return iterable<string, array{int}>
     */
    public static function invalidIdProvider(): iterable
    {
        yield '9333' => [9333];

        yield '9444' => [9444];

        yield 'zero' => [0];

        yield 'negative' => [-1];
    }

    public function testCreatePaymentMethodsByUnique(): void
    {
        $codes = ['paytabs_card', 'paytabs_sadad'];
        foreach ($codes as $code) {
            $method = PaymentMethodsFactory::createMethodByUnique($code);
            self::assertInstanceOf(AbstractMethod::class, $method);
        }
    }

    #[DataProvider('invalidCodeProvider')]
    public function testCreatePaymentMethodsByUniqueInvalid(string $code): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Payment Method not found for Unique Code: {$code}");

        PaymentMethodsFactory::createMethodByUnique($code);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function invalidCodeProvider(): iterable
    {
        yield 'unknown code' => ['test'];

        yield 'another unknown code' => ['test2'];

        yield 'empty code' => [''];
    }

    public function testCreatePaymentMethodFromEnum(): void
    {
        $newMethod = PaymentMethod::Halan->getMethodInstance();
        self::assertInstanceOf(AbstractMethod::class, $newMethod);
        self::assertInstanceOf(Halan::class, $newMethod);
    }

    public function testCreateAllPaymentMethodsFromEnum(): void
    {
        foreach (PaymentMethod::getAllMethods() as $methodEnum) {
            $newMethod = $methodEnum->getMethodInstance();
            self::assertInstanceOf(AbstractMethod::class, $newMethod);
            self::assertInstanceOf($methodEnum->value, $newMethod);
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

    public function testGetAllCurrencies(): void
    {
        $currencies = PaymentMethodsFactory::getAllCurrencies();
        self::assertIsArray($currencies);
        self::assertNotEmpty($currencies);
        self::assertContains('AED', $currencies);
    }
}
