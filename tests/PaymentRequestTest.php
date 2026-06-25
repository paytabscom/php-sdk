<?php

declare(strict_types=1);

define('APP_ROOT', realpath(__DIR__).'/../');

include_once APP_ROOT.'Samples/config.php';

use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Request\Payload\BuilderInterface;
use Paytabs\Sdk\Request\Payload\Parts\CustomerDetails;
use Paytabs\Sdk\Request\Payload\Parts\PaymentMethods;
use Paytabs\Sdk\Request\Payload\Parts\ShippingDetails;
use Paytabs\Sdk\Request\Payload\Payloads\HostedPage;
use Paytabs\Sdk\Request\Requests\PaymentRequest;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class PaymentRequestTest extends TestCase
{
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
    }

    public function testRequest(): void
    {
        if ('1' !== getenv('PAYTABS_RUN_LIVE_TESTS')) {
            self::markTestSkipped('Live payment request test skipped. Set PAYTABS_RUN_LIVE_TESTS=1 to run it.');
        }

        $profile = $this->generateProfile();
        $holder = $this->generatePayload();

        $request = new PaymentRequest($profile, $holder);

        $http = new Http();
        $http->setLogger(Paytabs::getLogger());
        $http->setRequest($request);
        $http->setDebugMode(false);

        $response1 = $http->submit();

        self::assertTrue($response1->isRedirect());

        $response2 = $http->submit();
        self::assertTrue($response2->isFailure(), 'Duplicate request');
    }

    private function generateProfile(): Profile
    {
        return new Profile(
            getConfig('ENDPOINT'),
            (int) getConfig('PROFILE_ID'),
            getConfig('SERVER_KEY')
        );
    }

    private function generatePayload(): BuilderInterface
    {
        $currency = getConfig('CURRENCY', 'AED');

        $holder = new HostedPage();
        $holder
            ->buildCart('c01', $currency, 100.51, 'Test')
            ->buildTransaction(TranType::Sale, TranClass::Ecom)
            ->buildPluginInfo('PHP', PHP_VERSION, null)
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
            // ->buildURLs(null, $urlCallback)
            ->buildAltCurrency('USD')
            // ->buildConfigId(11)
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
