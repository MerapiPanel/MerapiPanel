<?php

namespace Mp\Module\Segment;

class Service {

    protected $auth = false;

    public function __construct()
    {
        
        if(isset($_COOKIE["PHPSESSID"]))
        {
            $this->auth = true;
        }
        
    }

    public function __toString()
    {
        return $this->auth ? "admin" : "guest";
    }
}

