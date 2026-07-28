<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Payload\Paytabs;

use Paytabs\Sdk\Request\Payload\AbstractBuilder;
use Paytabs\Sdk\Request\Payload\Parts\PluginInfo;

abstract class PaytabsBuilder extends AbstractBuilder
{
    public function __construct(bool $autoFillPluginInfo = true)
    {
        $this->product = new PaytabsPayload();

        if ($autoFillPluginInfo) {
            $this->product->buildBody(
                new PluginInfo()
            );
        }
    }
}
