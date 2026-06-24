<?php

declare(strict_types=1);

use Paytabs\Sdk\Exceptions\EndpointNotFoundException;
use Paytabs\Sdk\Profile\Endpoint;
use Paytabs\Sdk\Profile\Endpoints\Egypt;
use Paytabs\Sdk\Profile\Endpoints\GlobalPt;
use Paytabs\Sdk\Profile\Endpoints\Iraq;
use Paytabs\Sdk\Profile\Endpoints\Jordan;
use Paytabs\Sdk\Profile\Endpoints\Ksa;
use Paytabs\Sdk\Profile\Endpoints\Kuwait;
use Paytabs\Sdk\Profile\Endpoints\Oman;
use Paytabs\Sdk\Profile\Endpoints\Uae;
use Paytabs\Sdk\Profile\EndpointsFactory;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
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
            self::assertInstanceOf(Endpoint::class, $endpoint);
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
}
