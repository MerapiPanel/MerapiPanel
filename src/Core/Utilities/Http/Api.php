<?php

namespace Mp\Core\Utilities\Http;

use Exception;
use Mp\Core\Box;
use Throwable;

class Api extends Response
{
    protected $request;
    protected $content = [];


    function setResponse($response = null)
    {
        $this->content['response'] = $response;
        $this->setContent($this->content);
    }

    function setCode($code = 200)
    {
        $this->content['status'] = $code;
        $this->setStatusCode($code);
        $this->setContent($this->content);
    }

    function getContent(): mixed
    {
        return $this->content;
    }
}
