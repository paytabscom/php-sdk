<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Responses\Webhook\TransactionResult;

class BrowserAsGet extends AbstractBrowser
{
    public static function init(array $localParams = []): self
    {
        $data = filter_input_array(INPUT_GET);

        return self::initWith($data, $localParams);
    }
}
