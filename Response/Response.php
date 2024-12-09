<?php

namespace Response;

class Response
{
    private $response;

    public function setResponse($response)
    {
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
