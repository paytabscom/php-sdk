<?php

namespace Paytabs\Sdk\Holder\Parts;

class Urls extends AbstractPart
{
    private ?string $returnUrl;
    private ?string $callbackUrl;

    private bool $returnUsingGet = false;

    //

    public function setUrls(
        ?string $returnUrl,
        ?string $callbackUrl
    ) {
        if ($this->readNextIf() === false) {
            return $this;
        }

        $this->returnUrl = $returnUrl;
        $this->callbackUrl = $callbackUrl;

        return $this;
    }

    public function setReturnUsingGet(bool $returnUsingGet = false): self
    {
        if ($this->readNextIf() === false) {
            return $this;
        }

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
