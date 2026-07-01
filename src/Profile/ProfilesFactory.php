<?php

namespace Paytabs\Sdk\Profile;

final class ProfilesFactory
{
    public static function createProfile(
        AbstractEndpoint|string $endpoint,
        int $profileId,
        string $serverKey
    ): Profile {
        $endpoint = $endpoint instanceof AbstractEndpoint
            ? $endpoint
            : EndpointsFactory::getEndpointByCode($endpoint);

        return new Profile(
            $endpoint,
            $profileId,
            $serverKey
        );
    }

    public static function createUaeProfile(int $profileId, string $serverKey): Profile
    {
        return self::createProfile(
            EndpointsFactory::getUaeEndpoint(),
            $profileId,
            $serverKey
        );
    }

    public static function createKsaProfile(int $profileId, string $serverKey): Profile
    {
        return self::createProfile(
            EndpointsFactory::getKsaEndpoint(),
            $profileId,
            $serverKey
        );
    }

    public static function createEgyptProfile(int $profileId, string $serverKey): Profile
    {
        return self::createProfile(
            EndpointsFactory::getEgyptEndpoint(),
            $profileId,
            $serverKey
        );
    }

    public static function createIraqProfile(int $profileId, string $serverKey): Profile
    {
        return self::createProfile(
            EndpointsFactory::getIraqEndpoint(),
            $profileId,
            $serverKey
        );
    }

    public static function createJordanProfile(int $profileId, string $serverKey): Profile
    {
        return self::createProfile(
            EndpointsFactory::getJordanEndpoint(),
            $profileId,
            $serverKey
        );
    }

    public static function createKuwaitProfile(int $profileId, string $serverKey): Profile
    {
        return self::createProfile(
            EndpointsFactory::getKuwaitEndpoint(),
            $profileId,
            $serverKey
        );
    }

    public static function createOmanProfile(int $profileId, string $serverKey): Profile
    {
        return self::createProfile(
            EndpointsFactory::getOmanEndpoint(),
            $profileId,
            $serverKey
        );
    }

    public static function createGlobalPtProfile(int $profileId, string $serverKey): Profile
    {
        return self::createProfile(
            EndpointsFactory::getGlobalPtEndpoint(),
            $profileId,
            $serverKey
        );
    }
}
