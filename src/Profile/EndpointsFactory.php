<?php

namespace Paytabs\Sdk\Profile;

use Paytabs\Sdk\Exceptions\EndpointNotFoundException;
use Paytabs\Sdk\Profile\Endpoints\Cuzdan;
use Paytabs\Sdk\Profile\Endpoints\Egypt;
use Paytabs\Sdk\Profile\Endpoints\GlobalPt;
use Paytabs\Sdk\Profile\Endpoints\Iraq;
use Paytabs\Sdk\Profile\Endpoints\Jordan;
use Paytabs\Sdk\Profile\Endpoints\Ksa;
use Paytabs\Sdk\Profile\Endpoints\Kuwait;
use Paytabs\Sdk\Profile\Endpoints\Madfoat;
use Paytabs\Sdk\Profile\Endpoints\Morocco;
use Paytabs\Sdk\Profile\Endpoints\Oman;
use Paytabs\Sdk\Profile\Endpoints\Qatar;
use Paytabs\Sdk\Profile\Endpoints\Uae;

final class EndpointsFactory
{
    /** @var array<string, class-string<AbstractEndpoint>> */
    private static array $endpointByCode = [
        Cuzdan::CODE => Cuzdan::class,
        Egypt::CODE => Egypt::class,
        GlobalPt::CODE => GlobalPt::class,
        Iraq::CODE => Iraq::class,
        Jordan::CODE => Jordan::class,
        Ksa::CODE => Ksa::class,
        Kuwait::CODE => Kuwait::class,
        Madfoat::CODE => Madfoat::class,
        Morocco::CODE => Morocco::class,
        Oman::CODE => Oman::class,
        Qatar::CODE => Qatar::class,
        Uae::CODE => Uae::class,
    ];

    public static function getEndpointByCode(string $code): AbstractEndpoint
    {
        $normalizedCode = strtoupper($code);

        if (!isset(self::$endpointByCode[$normalizedCode])) {
            throw EndpointNotFoundException::forCode($code);
        }

        $endpointClass = self::$endpointByCode[$normalizedCode];

        return $endpointClass::getInstance();
    }

    /** @return AbstractEndpoint[] */
    public static function getAllEndpoints(): array
    {
        $endpoints = [];

        foreach (self::$endpointByCode as $endpointClass) {
            $endpoints[] = $endpointClass::getInstance();
        }

        return $endpoints;
    }

    /**
     * @return array<string, string> an associative array where the keys are the endpoint codes and the values are the endpoint titles
     */
    public static function getAllEndpointsAsList(): array
    {
        $endpointsAsOptions = [];
        foreach (self::getAllEndpoints() as $endpoint) {
            $endpointsAsOptions[$endpoint->getCode()] = $endpoint->getTitle();
        }

        return $endpointsAsOptions;
    }

    // Create specific methods for known endpoints

    public static function getCuzdanEndpoint(): Cuzdan
    {
        return Cuzdan::getInstance();
    }

    public static function getEgyptEndpoint(): Egypt
    {
        return Egypt::getInstance();
    }

    public static function getGlobalPtEndpoint(): GlobalPt
    {
        return GlobalPt::getInstance();
    }

    public static function getIraqEndpoint(): Iraq
    {
        return Iraq::getInstance();
    }

    public static function getJordanEndpoint(): Jordan
    {
        return Jordan::getInstance();
    }

    public static function getKsaEndpoint(): Ksa
    {
        return Ksa::getInstance();
    }

    public static function getKuwaitEndpoint(): Kuwait
    {
        return Kuwait::getInstance();
    }

    public static function getMadfoatEndpoint(): Madfoat
    {
        return Madfoat::getInstance();
    }

    public static function getMoroccoEndpoint(): Morocco
    {
        return Morocco::getInstance();
    }

    public static function getOmanEndpoint(): Oman
    {
        return Oman::getInstance();
    }

    public static function getQatarEndpoint(): Qatar
    {
        return Qatar::getInstance();
    }

    public static function getUaeEndpoint(): Uae
    {
        return Uae::getInstance();
    }
}
