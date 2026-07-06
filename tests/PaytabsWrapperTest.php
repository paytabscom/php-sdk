<?php

declare(strict_types=1);

use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\PaytabsLogger;
use Paytabs\Sdk\Profile\ProfilesFactory;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @internal
 *
 * @coversNothing
 */
final class PaytabsWrapperTest extends TestCase
{
    public function testSetRequestInjectsProfileWhenMissing(): void
    {
        $profile = ProfilesFactory::createUaeProfile(12345, 'SRV_KEY_TEST');
        $holder = PayloadsFactory::createHostedPage();

        $request = RequestsFactory::createPaymentRequest($holder);
        self::assertFalse($request->isProfileSet());

        $paytabs = Paytabs::getInstance($profile, Http::create());
        $paytabs->setRequest($request);

        self::assertTrue($request->isProfileSet());
    }

    public function testGetLoggerReturnsLoggerInterface(): void
    {
        $logger = PaytabsLogger::getInstance()->logger;

        self::assertInstanceOf(LoggerInterface::class, $logger);
    }

    public function testGetLoggerReturnsProvidedLoggerAsIs(): void
    {
        $logger = new NullLogger();

        self::assertSame($logger, PaytabsLogger::getInstance($logger)->logger);
    }
}
