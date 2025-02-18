<?php

namespace Paytabs\Sdk\Response\Responses\Webhook\TransactionResult;

class BrowserCallback extends BrowserReturn
{
    protected array $localParams;

    public static function init(array $localParams = []): self
    {
        $data = filter_input_array(INPUT_GET);

        if (!$data) {
            throw new \Exception('Invalid init');
        }

        $dataJson = json_encode($data);

        return new self($dataJson, $data, $localParams);
    }
}
