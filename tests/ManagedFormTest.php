<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Paytabs\Sdk\Request\Payload\Payloads\ManagedForm;
use Paytabs\Sdk\Request\Payload\Parts\ManagedFormToken;

final class ManagedFormTest extends TestCase
{
    public function testBuildPaymentToken(): void
    {
        // Arrange
        $paymentToken = 'test_payment_token_123';

        // Create an instance of ManagedForm
        $managedForm = new ManagedForm();

        // Act
        $result = $managedForm->buildPaymentToken($paymentToken);

        // Assert
        $this->assertSame($managedForm, $result); // Ensure the method returns $this
    }

}
