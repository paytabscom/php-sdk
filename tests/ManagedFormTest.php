<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Tests;

use Paytabs\Sdk\Request\Payload\Payloads\ManagedForm;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ManagedFormTest extends TestCase
{
    public function testBuildPaymentTokenIsFluent(): void
    {
        $managedForm = new ManagedForm();

        $result = $managedForm->buildPaymentToken('test_payment_token_123');

        self::assertSame($managedForm, $result);
    }

    public function testBuildPaymentTokenReachesThePayloadBody(): void
    {
        $paymentToken = 'test_payment_token_123';

        $managedForm = new ManagedForm();
        $managedForm->buildPaymentToken($paymentToken);

        $body = $managedForm->getPayload()->getBody();

        self::assertIsArray($body);
        self::assertArrayHasKey('payment_token', $body);
        self::assertSame($paymentToken, $body['payment_token']);
    }
}
