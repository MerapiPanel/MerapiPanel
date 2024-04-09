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
        $this->accessName = isset($_ENV['__MP_ACCESS__']) ? $_ENV['__MP_ACCESS__'] : "guest";
    }



    public function __call($name, $arguments)
    {
        $default_api = Box::module(ucfirst($name))->Views->Api;

        if ($this->accessName != "guest") {
            $accessName = ucfirst($this->accessName);
            if (Box::module(ucfirst($name))->Views->$accessName) {
                $_api = Box::module(ucfirst($name))->Views->$accessName->Api;

                if ($_api) {
                    $default_api = $_api;
                }
            }
        }

        return $default_api;
    }
}