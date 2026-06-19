<?php

use Paytabs\Sdk\Profile\Endpoints\Uae;

function readConfigs(): array
{
    return [
        'endpoint' => Uae::getInstance(),
        'profile_id' => 100000,
        'server_key' => 'SR_SANDBOX_SERVER_KEY',

        'base_url' => 'https://example.test/paytabs-sample',

        'trx_ref' => 'TST_REFERENCE_PLACEHOLDER',

        'token' => 'TOKEN_PLACEHOLDER',
        'token_enhanced' => 'TOKEN_ENHANCED_PLACEHOLDER',

        'invoice_id' => 1234,

        'config_id' => 1000,

        'currency' => 'AED',

        'payment_token' => '',

        'phone_number' => '+971500000000',
    ];
}
