<?php

namespace Paytabs\Sdk\Holder\Parts;

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
        $this->platformVersion = $platformVersion ?? phpversion();
        $this->pluginVersion = $pluginVersion ?? Paytabs::getVersion();
    }

    public function build(): array
    {
        return [
            'cart_name' => $this->platformName,
            'cart_version' => $this->platformVersion,
            'plugin_version' => $this->pluginVersion,
        ];
    }
}
