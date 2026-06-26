<?php

use Paytabs\Sdk\Exceptions\HttpRequestException;
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

$request = RequestsFactory::createTokenQuery($profile, $holder);

Paytabs::getLogger()->debug(
    'TokenQuery holder Payload',
    $holder->getPayload()->getBody()
);

$http->setRequest($request);

try {
    $response = $http->submit();
} catch (HttpRequestException $e) {
    Paytabs::getLogger()->error('TokenQuery transport error', [
        'message' => $e->getMessage(),
    ]);

    throw $e;
}

if ($response->isFailure()) {
    $failure = $response->getFailure();
    Paytabs::getLogger()->error('TokenQuery failure', [
        'code' => $failure->code,
        'message' => $failure->message,
    ]);
}

Paytabs::getLogger()->debug(
    'TokenQuery Response',
    [
        // 'Response' => $response,
        'Mapped Auto' => $response->getPayloadMapped(),
    ]
);
