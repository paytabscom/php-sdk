<?php

namespace Paytabs\Sdk\Response\Responses\Webhook\TransactionResult;

class BrowserAsGet extends Browser
{
    public static function init(array $localParams = []): self
    {
        $data = filter_input_array(INPUT_GET);

        return self::initWith($data, $localParams);
    }
}
