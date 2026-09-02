<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Tests;

use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Profile\EndpointsFactory;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Request\Payload\BuilderInterface;
use Paytabs\Sdk\Request\Payload\Parts\CustomerDetails;
use Paytabs\Sdk\Request\Payload\Parts\PaymentMethods;
use Paytabs\Sdk\Request\Payload\Parts\ShippingDetails;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\Requests\PaymentRequest;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * The offline tests here must stay hermetic: they may not read env vars
 * and may not touch the network. Only the live
 * test opts in to real credentials, via environment variables.
 *
 * @internal
 */
final class PaymentRequestTest extends TestCase
{
    /** Placeholder credentials — shape-valid, never sent anywhere. */
    private const TEST_PROFILE_ID = 100001;
    private const TEST_SERVER_KEY = 'AAAAAAAAA9-AAAAAAAAA9-AAAAAAAAAA';

    public function testGeneratedPayload(): void
    {
        $holder = $this->generatePayload();
        $payload = $holder->getPayload()->getBody();

        self::assertIsArray($payload);
        self::assertArrayHasKey('cart_id', $payload);
        self::assertArrayHasKey('payment_methods', $payload);
        self::assertIsArray($payload['payment_methods']);
    }

    public function testGenerateProfile(): void
    {
        $profile = $this->generateProfile();

        $payload = $profile->getBody();

        self::assertIsArray($payload);
        self::assertArrayHasKey('profile_id', $payload);
        self::assertSame(self::TEST_PROFILE_ID, $payload['profile_id']);
    }

    public function testRequest(): void
    {
        if ('1' !== getenv('PAYTABS_RUN_LIVE_TESTS')) {
            self::markTestSkipped('Live payment request test skipped. Set PAYTABS_RUN_LIVE_TESTS=1 to run it.');
        }

        $profileId = getenv('PAYTABS_PROFILE_ID');
        $serverKey = getenv('PAYTABS_SERVER_KEY');
        $endpointCode = getenv('PAYTABS_ENDPOINT_CODE') ?: 'ARE';

        if (!$profileId || !$serverKey) {
            self::markTestSkipped(
                'Live test needs PAYTABS_PROFILE_ID and PAYTABS_SERVER_KEY '
                . '(and optionally PAYTABS_ENDPOINT_CODE, default ARE).'
            );
        }

        $profile = new Profile(
            EndpointsFactory::getEndpointByCode($endpointCode),
            (int) $profileId,
            $serverKey
        );

        $holder = $this->generatePayload(uniqid('cart-', true));

        $request = new PaymentRequest($holder, $profile);

        $http = new Http();
        $http->setLogger(new NullLogger());
        $http->setRequest($request);
        $http->setDebugMode(false);

        $response = $http->submit();

        self::assertTrue($response->isRedirect());
    }

    private function generateProfile(): Profile
    {
        return new Profile(
            EndpointsFactory::getUaeEndpoint(),
            self::TEST_PROFILE_ID,
            self::TEST_SERVER_KEY
        );
    }

    private function generatePayload(string $cartId = 'c01'): BuilderInterface
    {
        $holder = PayloadsFactory::createHostedPage();
        $holder
            ->buildCart($cartId, 'AED', 100.51, 'Test')
            ->buildTransaction(TranType::Sale, TranClass::Ecom)
            ->buildPluginInfo('PHP-SDK', PHP_VERSION, null)
            ->buildCustomerDetails(
                CustomerDetails::init('Wajih', '0522222222', 'wajih@mail.com')
                    ->setAddress('ARE', 'Dubai', 'Dubai', null, '11111')
                    ->setIp('1.1.1.1')
            )
            ->buildShippingDetails(
                ShippingDetails::init('Wajih 2')
            )
            ->buildHideShipping(true)
            ->buildTokenise(true)
            ->buildPaymentMethods(
                PaymentMethods::init()
                    ->includeMethod('card')
                    ->nextIf(true)
                    ->excludeMethod('tabby')
                    ->includeMethods(['card', 'tamara'])
                    ->excludeMethods(['applepay', 'samsungpay'])
            )
        ;

        return $holder;
    }
}
