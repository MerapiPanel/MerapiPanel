<?php

namespace Mp\Core\Utilities\Http;

class Http_Api extends Response
{
    protected $request;
    public function __construct(Request $request)
    {

        $this->request = $request;
        parent::__construct(["statusCode" => 200, "content" => null]);
    }


    function __toString()
    {

        $data = is_string($this->content) ? json_decode($this->content, true) : $this->content;
        $data['execution_time'] = (microtime(true) - floatval($GLOBALS['time_start'])) . " seconds";

        return json_encode($data);
    }
}
