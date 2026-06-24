<?php

namespace Paytabs\Sdk\Profile;

use Paytabs\Sdk\Exceptions\EndpointNotFoundException;
use Paytabs\Sdk\Profile\Endpoints\Egypt;
use Paytabs\Sdk\Profile\Endpoints\GlobalPt;
use Paytabs\Sdk\Profile\Endpoints\Iraq;
use Paytabs\Sdk\Profile\Endpoints\Jordan;
use Paytabs\Sdk\Profile\Endpoints\Ksa;
use Paytabs\Sdk\Profile\Endpoints\Kuwait;
use Paytabs\Sdk\Profile\Endpoints\Oman;
use Paytabs\Sdk\Profile\Endpoints\Uae;

final class EndpointsFactory
{
    /** @var array<string, class-string<Endpoint>> */
    private static array $endpointByCode = [
        Uae::CODE => Uae::class,
        Ksa::CODE => Ksa::class,
        Egypt::CODE => Egypt::class,
        Iraq::CODE => Iraq::class,
        Jordan::CODE => Jordan::class,
        Kuwait::CODE => Kuwait::class,
        Oman::CODE => Oman::class,
        GlobalPt::CODE => GlobalPt::class,
    ];

    public static function getEndpointByCode(string $code): Endpoint
    {
        $normalizedCode = strtoupper($code);

        if (!isset(self::$endpointByCode[$normalizedCode])) {
            throw EndpointNotFoundException::forCode($code);
        }

        $endpointClass = self::$endpointByCode[$normalizedCode];

        return $endpointClass::getInstance();
    }

    public static function getUaeEndpoint(): Uae
    {
        return Uae::getInstance();
    }

    public static function getKsaEndpoint(): Ksa
    {
        return Ksa::getInstance();
    }

    public static function getEgyptEndpoint(): Egypt
    {
        return Egypt::getInstance();
    }

    public static function getIraqEndpoint(): Iraq
    {
        return Iraq::getInstance();
    }

    public static function getJordanEndpoint(): Jordan
    {
        return Jordan::getInstance();
    }

    public static function getKuwaitEndpoint(): Kuwait
    {
        return Kuwait::getInstance();
    }

    public static function getOmanEndpoint(): Oman
    {
        return Oman::getInstance();
    }

    public static function getGlobalPtEndpoint(): GlobalPt
    {
        return GlobalPt::getInstance();
    }
}
