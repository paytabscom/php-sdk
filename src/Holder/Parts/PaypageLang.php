<?php

namespace Paytabs\Sdk\Holder\Parts;

use Holder\PartInterface;

class PaypageLang implements PartInterface
{
    public string $paypageLang;

    public function __construct(
        string $paypageLang
    ) {
        $this->paypageLang = $paypageLang;
    }

    public function build(): array
    {
        return [
            'paypage_lang' => $this->paypageLang,
        ];
    }
}
