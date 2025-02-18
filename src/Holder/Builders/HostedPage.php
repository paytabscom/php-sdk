<?php

namespace Paytabs\Sdk\Holder\Builders;

use Paytabs\Sdk\Holder\Parts\AltCurrency;
use Paytabs\Sdk\Holder\Parts\Framed;
use Paytabs\Sdk\Holder\Parts\HideShipping;
use Paytabs\Sdk\Holder\Parts\ConfigId;
use Paytabs\Sdk\Holder\Parts\CardFilter;

class HostedPage extends PrimaryPayment
{
    public function buildHideShipping(bool $hideShipping = true)
    {
        $this->product->buildBody(
            new HideShipping($hideShipping)
        );

        return $this;
    }

    /**
     * @param string $redirect_target "parent" or "top" or "iframe"
     */
    public function buildFramedObj(Framed $framed)
    {
        $this->product->buildBody($framed);

        return $this;
    }

    public function buildAltCurrency(string $altCurrency)
    {
        $this->product->buildBody(
            new AltCurrency($altCurrency)
        );

        return $this;
    }

    public function buildConfigId(int $configId)
    {
        $this->product->buildBody(
            new ConfigId($configId)
        );

        return $this;
    }

    public function buildCardFilter(string $cardFilter, string $cardFilterTitle)
    {
        $this->product->buildBody(
            new CardFilter($cardFilter, $cardFilterTitle)
        );

        return $this;
    }
}
