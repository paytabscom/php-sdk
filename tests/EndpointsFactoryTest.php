<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Tests;

use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Exceptions\EndpointNotFoundException;
use Paytabs\Sdk\Profile\AbstractEndpoint;
use Paytabs\Sdk\Profile\Endpoints\Egypt;
use Paytabs\Sdk\Profile\Endpoints\GlobalPt;
use Paytabs\Sdk\Profile\Endpoints\Iraq;
use Paytabs\Sdk\Profile\Endpoints\Jordan;
use Paytabs\Sdk\Profile\Endpoints\Ksa;
use Paytabs\Sdk\Profile\Endpoints\Kuwait;
use Paytabs\Sdk\Profile\Endpoints\Oman;
use Paytabs\Sdk\Profile\Endpoints\Uae;
use Paytabs\Sdk\Profile\EndpointsFactory;
use Paytabs\Sdk\Profile\ProfilesFactory;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;
use Paytabs\Sdk\Tests\Doubles\WhiteLabelEndpoint;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class EndpointsFactoryTest extends TestCase
{
    public function testGetEndpointByCode(): void
    {
        $cases = [
            'ARE' => Uae::class,
            'sau' => Ksa::class,
            'EGY' => Egypt::class,
            'irq' => Iraq::class,
            'jor' => Jordan::class,
            'KWT' => Kuwait::class,
            'omn' => Oman::class,
            'global' => GlobalPt::class,
        ];

        foreach ($cases as $code => $expectedClass) {
            $endpoint = EndpointsFactory::getEndpointByCode($code);
            self::assertInstanceOf(AbstractEndpoint::class, $endpoint);
            self::assertInstanceOf($expectedClass, $endpoint);
        }
    }

    public function testGetEndpointByCodeInvalid(): void
    {
        $this->expectException(EndpointNotFoundException::class);
        EndpointsFactory::getEndpointByCode('UNKNOWN');
    }

    public function testGetEndpointByCodeReturnsSingletonInstance(): void
    {
        $fromCode = EndpointsFactory::getEndpointByCode('ARE');
        $direct = EndpointsFactory::getUaeEndpoint();

        self::assertSame($direct, $fromCode);
    }

    public function testGetAllEndpoints(): void
    {
        $endpoints = EndpointsFactory::getAllEndpoints();

        self::assertIsArray($endpoints);
        self::assertNotEmpty($endpoints);

        foreach ($endpoints as $endpoint) {
            self::assertInstanceOf(AbstractEndpoint::class, $endpoint);
        }
    }

    /**
     * The shipped list is not exhaustive: PayTabs is white-labelled and regions
     * are added over time. A merchant on a host this SDK does not know must be
     * able to supply it without waiting for a release, so keep
     * AbstractEndpoint extensible and keep createProfile() accepting an
     * instance rather than only a code.
     */
    public function testAnEndpointOutsideTheShippedListCanBeUsed(): void
    {
        $profile = ProfilesFactory::createProfile(
            WhiteLabelEndpoint::getInstance(),
            100001,
            'AAAAAAAAA9-BBBBBBBBB8-CCCCCCCCC7'
        );

        $payload = PayloadsFactory::createHostedPage();
        $payload
            ->buildTransaction(TranType::Sale, TranClass::Ecom)
            ->buildCart('cart-1', 'AED', 10.0, 'Custom endpoint')
        ;

        $request = RequestsFactory::createPaymentRequest($payload, $profile);

        self::assertSame('https://pay.some-bank.example/payment/request', $request->getUrl());

        // Unlisted by design: it is not registered with the factory.
        self::assertNotContains(
            WhiteLabelEndpoint::getInstance(),
            EndpointsFactory::getAllEndpoints()
        );
    }
}
