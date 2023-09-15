<?php

namespace Mp\Core\Utilities\Http;

use Exception;
use Mp\Core\Box;

class Api extends Response
{
    protected $request;
    protected $box;


    public function __construct(Request $request)
    {

        parent::__construct(["status" => 200, "response" => null]);
        $this->request = $request;
        $this->setHeader("Content-Type", "application/json");
    }


    public function setBox(Box $box)
    {
        $this->box = $box;
    }


    function __toString()
    {

        if (!isset($this->box)) {
            throw new Exception("Box not set");
        }
        foreach ($this->getHeaders() as $key => $value) {
            header("$key: $value");
        };

        $data = is_string($this->content) ? json_decode($this->content, true) : $this->content;
        $data['execution_time'] = (microtime(true) - floatval($GLOBALS['time_start'])) . " seconds";

        return json_encode($data);
    }
}
