<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response;

use Paytabs\Sdk\Enums\ResponseStage;
use Paytabs\Sdk\Exceptions\UnexpectedResponseStageException;
use Paytabs\Sdk\Helpers\Helpers;
use Paytabs\Sdk\Request\RequestInterface;
use Paytabs\Sdk\Response\Payload\PayloadInterface;
use Paytabs\Sdk\Response\Payload\Payloads\Failure;
use Paytabs\Sdk\Response\Payload\Payloads\Redirect;

abstract class AbstractResponseDirect extends AbstractResponse implements ResponseDirectInterface
{
    protected RequestInterface $request;

    protected int $responseCode;

    public function setResponseCode(int $responseCode): static
    {
        $this->responseCode = $responseCode;

        return $this;
    }

    public function setRequest(RequestInterface $request): void
    {
        $this->request = $request;
    }

    public function isProcessed(): bool
    {
        return $this->responseCode >= 200 && $this->responseCode < 300;
    }

    public function isFailure(): bool
    {
        return ResponseStage::Error === Helpers::responseStage($this->payload->getAsJson());
    }

    public function getFailure(): Failure
    {
        if (!$this->isFailure()) {
            throw UnexpectedResponseStageException::forStage('failure');
        }

        return $this->payload->getMappedAs(new Failure());
    }

    public function isRedirect(): bool
    {
        return ResponseStage::Redirect === Helpers::responseStage($this->payload->getAsJson());
    }

    public function getRedirect(): Redirect
    {
        if (!$this->isRedirect()) {
            throw UnexpectedResponseStageException::forStage('redirect');
        }

        return $this->payload->getMappedAs(new Redirect());
    }

    /**
     * @throws \InvalidArgumentException if the payload is not valid or cannot be mapped.
     */
    public function getPayloadMapped(): PayloadInterface
    {
        if ($this->isFailure()) {
            return $this->getFailure();
        }
        if ($this->isRedirect()) {
            return $this->getRedirect();
        }

        return $this->payload->getMapped();
    }
}
