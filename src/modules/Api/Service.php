<?php

namespace il4mb\Mpanel\Modules\Api;

class Service
{

    protected $box;

    public function setBox($box)
    {
        $this->box = $box;
    }

    public function __call($name, $arguments)
    {
        $parts = explode("_", $name);

        print_r($parts);
        $name = ucfirst($parts[0]);

        return $this->box->$name();
    }

    // public function hallo()
    // {
    //     echo "Hallo";
    // }
}