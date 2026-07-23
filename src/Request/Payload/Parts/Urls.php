<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Payload\Parts;

class Urls extends AbstractPart
{
    private ?string $returnUrl;
    private ?string $callbackUrl;

    private bool $returnUsingGet = false;

    public function __construct(
        ?string $returnUrl,
        ?string $callbackUrl = null
    ) {
        if ($returnUrl) {
            if (!filter_var($returnUrl, FILTER_VALIDATE_URL)) {
                throw new \InvalidArgumentException('Invalid return URL');
            }
            $this->returnUrl = $returnUrl;
        }
        if ($callbackUrl) {
            if (!filter_var($callbackUrl, FILTER_VALIDATE_URL)) {
                throw new \InvalidArgumentException('Invalid callback URL');
            }
            $this->callbackUrl = $callbackUrl;
        }
    }

    public function setReturnUsingGet(bool $returnUsingGet = false): self
    {
        $this->returnUsingGet = $returnUsingGet;

        return $this;
    }

    public function build(): array
    {
        $arr = [];

        if (isset($this->returnUrl)) {
            $arr['return'] = $this->returnUrl;

            if ($this->returnUsingGet) {
                $arr['return_using_get'] = true;
            }
        }

        if (isset($this->callbackUrl)) {
            $arr['callback'] = $this->callbackUrl;
        }

        return $arr;
    }
}
