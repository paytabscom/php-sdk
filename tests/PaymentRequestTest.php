<?php

declare(strict_types=1);

use Paytabs\Sdk\Enums\ResponseStage;
use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Gateway\Endpoints\Uae;
use Paytabs\Sdk\Gateway\Gateway;
use Paytabs\Sdk\Holder\BuilderInterface;
use Paytabs\Sdk\Holder\Builders\HostedPage;
use Paytabs\Sdk\Holder\Parts\CustomerDetails;
use Paytabs\Sdk\Holder\Parts\PaymentMethods;
use Paytabs\Sdk\Holder\Parts\ShippingDetails;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Requests\PaymentRequest;
use PHPUnit\Framework\TestCase;

final class PaymentRequestTest extends TestCase
{
    private function generateGateway(): Gateway
    {
        return new Gateway(
            Uae::getInstance(),
            47170,
            'SRJNLKK2Z2-HWRGM6JDZM-MGMGGNW9JZ'
        );
    }

    private function generatePayload(): BuilderInterface
    {
        $holder = new HostedPage();
        $holder
            ->buildCart("c01", "AED", 100.51, "Test")
            ->buildTransaction(TranType::Sale, TranClass::Ecom)
            ->buildPluginInfo('PHP', PHP_VERSION, null)
            ->buildCustomerDetails(
                (new CustomerDetails('Wajih', '0522222222', 'wajih@mail.com'))
                    ->setAddress('ARE', 'Dubai', 'Dubai', null, '11111')
                    ->setIp('1.1.1.1')
            )
            ->buildShippingDetails(
                new ShippingDetails('Wajih 2')
            )
            ->buildHideShipping(true)
            ->buildTokenise(true)
            // ->buildURLs(null, $urlCallback)
            ->buildAltCurrency('USD')
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

    public function testGeneratedPayload(): void
    {
        $holder = $this->generatePayload();
        $payload = $holder->getPayload()->getBody();

        self::assertIsArray($payload);
        self::assertArrayHasKey('cart_id', $payload);
        self::assertArrayHasKey('payment_methods', $payload);
        self::assertIsArray($payload['payment_methods']);
    }

    public function testGenerateGateway(): void
    {
        $gateway = $this->generateGateway();

        $payload = $gateway->getBody();

        self::assertIsArray($payload);
        self::assertArrayHasKey('profile_id', $payload);
    }

    public function testRequest(): void
    {
        $gateway = $this->generateGateway();
        $holder = $this->generatePayload();

        $request = new PaymentRequest($gateway, $holder);

        $http = new Http();
        $http->setLogger(Paytabs::Logger());
        $http->setRequest($request);
        $http->setDebugMode(false);

        $response = $http->submit();
        $responseStage = $response->getResponseStage();

        self::assertSame($responseStage, ResponseStage::Redirect);
    }
}
