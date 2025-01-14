<?php

namespace Paytabs\Sdk\Holder\Parts;

class PaypageLang extends AbstractPart
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
