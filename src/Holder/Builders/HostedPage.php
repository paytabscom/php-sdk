<?php

namespace Paytabs\Sdk\Holder\Builders;

use Paytabs\Sdk\Holder\Parts\AltCurrency;
use Paytabs\Sdk\Holder\Parts\CardFilter;
use Paytabs\Sdk\Holder\Parts\ConfigId;
use Paytabs\Sdk\Holder\Parts\Donation;
use Paytabs\Sdk\Holder\Parts\Framed;
use Paytabs\Sdk\Holder\Parts\HideShipping;

class HostedPage extends PrimaryPayment
{
    public function buildHideShipping(bool $hideShipping = true)
    {
        $this->product->buildBody(
            new HideShipping($hideShipping)
        );

        return $this;
    }

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

    public function buildDonationMode(bool $donationMode, float $cartMin, float $cartMax)
    {
        $this->product->buildBody(
            new Donation($donationMode, $cartMin, $cartMax)
        );

        return $this;
    }
}
