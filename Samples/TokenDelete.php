<?php

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

$request = RequestsFactory::createTokenDelete($holder);

$paytabs->setRequest($request);

$response = $paytabs->submit();

$logger->debug(
    'TokenDelete Response',
    [
        'Response' => $response,
        'Mapped Auto' => $response->getPayloadMapped(),
    ]
);
