<?php

use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;

/**
 * @var Profile $profile
 * @var string $_token
 * @var Http $http
 */

if (!isset($profile, $_token, $http)) {
    throw new \RuntimeException('Required variables are not set: $profile, $_token, $http');
}

//

$holder = PayloadsFactory::token();
$holder->buildToken($_token);

$request = RequestsFactory::tokenQuery($profile, $holder);

Paytabs::getLogger()->debug(
    'TokenQuery holder Payload',
    $holder->getPayload()->getBody()
);

$http->setRequest($request);

$response = $http->submit();

Paytabs::getLogger()->debug(
    'TokenQuery Response',
    [
        // 'Response' => $response,
        'Mapped Auto' => $response->getPayloadMapped(),
    ]
);
