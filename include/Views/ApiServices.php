<?php
namespace MerapiPanel\Views {

    use MerapiPanel\Box;
    use MerapiPanel\Box\Module\Entity\Module;

    class ApiServices
    {

        protected $accessName = "guest";


        public function __construct()
        {
            $this->accessName = isset($_ENV['__MP_ACCESS__']) ? $_ENV['__MP_ACCESS__'] : "guest";
        }



        public function __call($name, $arguments)
        {

            return new ApiProxy($name);
        }
    }



    class ApiProxy
    {

        protected Module $module;
        protected $accessName = "guest";

        public function __construct($module)
        {
            $this->accessName = isset($_ENV['__MP_ACCESS__']) ? $_ENV['__MP_ACCESS__'] : "guest";
            $this->module = Box::module($module);
        }


        public function __call($name, $arguments)
        {
            $default_api = $this->module->Views->Api;
            if ($default_api && (method_exists($default_api, $name) || (method_exists($default_api, "method_exists") && $default_api->method_exists($name)))) {
                return $default_api->$name(...$arguments);
            } else if ($this->accessName != "guest") {
                $accessName = ucfirst($this->accessName);
                if ($this->module->Views->$accessName) {
                    $_api = $this->module->Views->$accessName->Api;
                    if ($_api && (method_exists($_api, $name) || (method_exists($_api, "method_exists") && $_api->method_exists($name)))) {
                        return $_api->$name(...$arguments);
                    }
                }
            }

            return null;
        }
    }
}