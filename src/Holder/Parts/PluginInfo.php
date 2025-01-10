<?php

namespace Paytabs\Sdk\Holder\Parts;

use Holder\PartInterface;

class PluginInfo implements PartInterface
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
        // @todo
        $this->pluginVersion = $pluginVersion ?? 'PAYTABS_SDK_VERSION';
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
