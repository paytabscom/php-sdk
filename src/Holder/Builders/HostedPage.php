<?php

namespace Paytabs\Sdk\Holder\Builders;

use Holder\Parts\AltCurrency;
use Holder\Parts\Framed;
use Holder\Parts\HideShipping;

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
}
