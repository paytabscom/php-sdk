<?php

namespace Paytabs\Sdk\Response\Responses\Webhook\TransactionResult;

class BrowserAsPost extends AbstractBrowser
{
    public static function init(array $localParams = []): self
    {
        $data = filter_input_array(INPUT_POST);

        return self::initWith($data, $localParams);
    }
}
