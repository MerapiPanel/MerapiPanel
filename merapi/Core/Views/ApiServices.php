<?php
namespace MerapiPanel\Core\Views;

use MerapiPanel\Box;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Utility\Util;

class ApiServices
{
    protected $accessName = "guest";
    protected $callerModuleClassName = false;

    public function __construct()
    {

        $this->accessName = Util::getAccessName();

    }


    function __call($method, $args)
    {
        $this->accessName = Util::getAccessName(); // update access name

        if (Module::moduleExist($method)) {

            if ($this->accessName != "guest" && class_exists("\\MerapiPanel\\Module\\" . $method . "\\Views\\" . (strtolower($this->accessName)) . "\\Api")) {
                $instanceClass = "\\MerapiPanel\\Module\\" . $method . "\\Views\\" . (strtolower($this->accessName)) . "\\Api";
                return Box::get($this)->$instanceClass();
            } 
            
            if (class_exists("\\MerapiPanel\\Module\\" . $method . "\\Views\\Api")) {
                $instanceClass = "\\MerapiPanel\\Module\\" . $method . "\\Views\\Api";
                return Box::get($this)->$instanceClass();
            }

            throw new \Exception("Module $method does't provide API for " . $this->accessName);
        }
        throw new \Exception("Module not found: " . $method);
    }




    function __get($name)
    {

        error_log("Property not found: " . $name);
    }


    private function clarifyCall($method)
    {


        $baseon = "\\MerapiPanel\\Module\\";
        $parts = array_filter(explode("_", $method));

    }
}