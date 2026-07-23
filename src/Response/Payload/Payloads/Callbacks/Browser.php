<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Payload\Payloads\Callbacks;

use Paytabs\Sdk\Enums\TranStatus;
use Paytabs\Sdk\Exceptions\UnknownResponseValueException;
use Paytabs\Sdk\PaytabsLogger;
use Paytabs\Sdk\Response\Payload\Payloads\Paytabs;

class Browser extends Paytabs
{
    public string $acquirerMessage;
    public string $acquirerRRN;

    public string $tranRef;
    public string $cartId;

    public string $customerEmail;

    public string $respStatus;
    public TranStatus $tranStatus;

    public string $respMessage;
    public string $respCode;

    public string $signature;

    public string $token;

    public function setRespStatus(string $respStatus): void
    {
        $this->respStatus = $respStatus;
        $this->tranStatus = TranStatus::tryFrom(strtoupper($respStatus)) ?? TranStatus::Unknown;

        if (TranStatus::Unknown === $this->tranStatus) {
            if (self::isStrictMode()) {
                throw UnknownResponseValueException::forTranStatus($respStatus);
            }

            PaytabsLogger::getInstance()->logger->error('Unknown transaction status', [
                'tran_status' => $respStatus,
            ]);
        }
    }

    public function isTransactionSuccessful(): bool
    {
        return $this->tranStatus->isSuccessful();
    }
}
