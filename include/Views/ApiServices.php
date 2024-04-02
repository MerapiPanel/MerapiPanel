<?php
namespace MerapiPanel\Views;

use MerapiPanel\Box;
use MerapiPanel\Abstract\Module;
use MerapiPanel\Utility\Util;

class ApiServices
{
    
    protected $accessName = "guest";


    public function __construct()
    {
        $this->accessName = Util::getAccessName();
    }



    public function __call($name, $arguments)
    {

        if ($this->accessName != "guest") {
            $accessName = ucfirst($this->accessName);
            $api = Box::module(ucfirst($name))->Views->$accessName->Api;
        } else {
            $api = Box::module(ucfirst($name))->Views->Api;
        }

        return $api;
    }
}