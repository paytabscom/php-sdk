<?php

declare(strict_types=1);

use Paytabs\Sdk\Exceptions\HttpRequestException;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;
use Psr\Log\LoggerInterface;

/**
 * @var string          $_token
 * @var Paytabs         $paytabs
 * @var LoggerInterface $logger
 */
if (!isset($paytabs, $_token, $logger)) {
    throw new RuntimeException('Required variables are not set: $paytabs, $_token, $logger');
}

$holder = PayloadsFactory::createToken();
$holder->buildToken($_token);

$request = RequestsFactory::createTokenQuery($holder);
$paytabs->setRequest($request);

$logger->debug(
    'TokenQuery holder Payload',
    $holder->getPayload()->getBody()
);

try {
    $response = $paytabs->submit();
} catch (HttpRequestException $e) {
    $logger->error('TokenQuery transport error', [
        'message' => $e->getMessage(),
    ]);

    throw $e;
}

if ($response->isFailure()) {
    $failure = $response->getFailure();
    $logger->error('TokenQuery failure', [
        'code' => $failure->code,
        'message' => $failure->message,
    ]);
}

$logger->debug(
    'TokenQuery Response',
    [
        // 'Response' => $response,
        'Mapped Auto' => $response->getPayloadMapped(),
    ]
);
