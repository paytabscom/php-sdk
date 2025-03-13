<?php

namespace Paytabs\Sdk\Request\Payload\Parts;

use Paytabs\Sdk\Paytabs;

class PluginInfo extends AbstractPart
{
    private string $platformName;
    private string $platformVersion;
    private string $pluginVersion;

    public function __construct(
        ?string $platformName,
        ?string $platformVersion,
        ?string $pluginVersion
    ) {
        $this->platformName = $platformName ?? 'PHP';
        $this->platformVersion = $platformVersion ?? PHP_VERSION;
        $this->pluginVersion = $pluginVersion ?? Paytabs::getVersion();
    }

    public function build(): array
    {
        return [
            'plugin_info' => [
                'cart_name' => $this->platformName,
                'cart_version' => $this->platformVersion,
                'plugin_version' => $this->pluginVersion,
            ],
        ];
    }
}
