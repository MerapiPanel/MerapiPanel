<?php

namespace il4mb\Mpanel\Core\Segment;

class Entity {

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

