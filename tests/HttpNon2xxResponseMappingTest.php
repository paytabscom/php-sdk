<?php

declare(strict_types=1);

use Paytabs\Sdk\Enums\HttpType;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Request\RequestInterface;
use Paytabs\Sdk\Response\Payload\PayloadInterface;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class HttpNon2xxResponseMappingTest extends TestCase
{
    public function testNon2xxWithJsonBodyIsMappedAsFailure(): void
    {
        $http = new class extends Http {
            protected function executeRequest(CurlHandle $curl_handle): array
            {
                return [
                    'response' => '{"code":401,"message":"Unauthorized"}',
                    'statusCode' => 401,
                    'errorNo' => 0,
                    'errorMessage' => '',
                ];
            }
        };

        $request = new class implements RequestInterface {
            public function getUrl(): string
            {
                return 'https://example.com/paytabs/mock';
            }

            public function getHeaders(): array
            {
                return ['Content-Type: application/json'];
            }

            public function getPayload(): array|string
            {
                return '{}';
            }

            public function getHttpType(): HttpType
            {
                return HttpType::POST;
            }

            public function isHttpPost(): bool
            {
                return true;
            }

            public function getResponseClass(): ?PayloadInterface
            {
                return null;
            }
        };

        $http->setRequest($request);
        $response = $http->submit();

        self::assertFalse($response->isProcessed());
        self::assertTrue($response->isFailure());

        $failure = $response->getFailure();
        self::assertSame(401, $failure->code);
    }
}
