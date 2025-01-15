<?php

namespace Paytabs\Sdk\Holder\Parts;

class Urls extends AbstractPart
{
    private ?string $returnUrl;
    private ?string $callbackUrl;

    private bool $returnUsingGet = false;

    //

    public function __construct(
        ?string $returnUrl,
        ?string $callbackUrl
    ) {
        $this->returnUrl = $returnUrl;
        $this->callbackUrl = $callbackUrl;
    }

    public function setReturnUsingGet(bool $returnUsingGet = false): self
    {
        $this->returnUsingGet = $returnUsingGet;

        return $this;
    }

    public function build(): array
    {
        $arr = [
            'return' => $this->returnUrl,
            'callback' => $this->callbackUrl,
        ];

        if ($this->returnUsingGet) {
            $arr['return_using_get'] = true;
        }

        return $arr;
    }
}
