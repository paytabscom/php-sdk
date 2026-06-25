<?php

use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;

/**
 * @var Profile $profile
 * @var string  $_token
 * @var Http    $http
 */
if (!isset($profile, $_token, $http)) {
    throw new RuntimeException('Required variables are not set: $profile, $_token, $http');
}

$holder = PayloadsFactory::token();
$holder->buildToken($_token);

$request = RequestsFactory::tokenDelete($profile, $holder);

// @var Http $http
$http->setRequest($request);

$response = $http->submit();

Paytabs::getLogger()->debug(
    'TokenDelete Response',
    [
        'Response' => $response,
        'Mapped Auto' => $response->getPayloadMapped(),
    ]
);
