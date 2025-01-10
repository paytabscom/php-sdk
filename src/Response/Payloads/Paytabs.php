<?php

namespace Paytabs\Sdk\Response\Payloads;

use JsonMapper;
use Response\PayloadInterface;

abstract class Paytabs implements PayloadInterface
{
    public string $trace;

    /*
    public function __construct($json)
    {
        $jsonArray = json_decode($json, true);

        foreach ($jsonArray as $key => $value) {
            if (is_scalar($this->$key)) {
                $this->$key = $value;
            } elseif (is_array($value)) {
                echo "Array";
            } elseif (is_object($value)) {
                echo "Object";
            }
        }
    }
    */

    public function init(): self
    {
        return new self();
    }

    public function fromJson($jsonResponse): self
    {
        $jsonMapper = new JsonMapper();
        return $jsonMapper->map($jsonResponse, $this);
    }
}
