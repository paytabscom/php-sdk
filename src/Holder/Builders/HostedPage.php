<?php

namespace Paytabs\Sdk\Holder\Builders;

use Paytabs\Sdk\Holder\Parts\AltCurrency;
use Paytabs\Sdk\Holder\Parts\CardDiscounts;
use Paytabs\Sdk\Holder\Parts\CardFilter;
use Paytabs\Sdk\Holder\Parts\ConfigId;
use Paytabs\Sdk\Holder\Parts\Donation;
use Paytabs\Sdk\Holder\Parts\Framed;

class HostedPage extends PrimaryPayment
{
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

    public function buildCardDiscounts(CardDiscounts $cardDiscounts)
    {
        $this->product->buildBody($cardDiscounts);

        return $this;
    }
}
