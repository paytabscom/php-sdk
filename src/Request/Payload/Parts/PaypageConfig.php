<?php

namespace Paytabs\Sdk\Request\Payload\Parts;

use Paytabs\Sdk\Enums\Language;

class PaypageConfig extends AbstractPart
{
    public ?string $paypageLang;
    public ?string $altCurrency;
    public ?int $configId;

    public function __construct(
        Language|string|null $paypageLang,
        ?string $altCurrency,
        ?int $configId,
    ) {
        $this->paypageLang = $paypageLang instanceof Language ? $paypageLang->value : $paypageLang;
        $this->altCurrency = $altCurrency;
        $this->configId = $configId;
    }

    public function build(): array
    {
        return [
            'paypage_lang' => $this->paypageLang,
            'alt_currency' => $this->altCurrency,
            'config_id' => $this->configId,
        ];
    }
}
