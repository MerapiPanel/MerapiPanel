<?php
namespace MerapiPanel\Views {

    use MerapiPanel\Box;
    use MerapiPanel\Box\Module\Entity\Module;
    use MerapiPanel\Box\Module\Entity\Proxy;

    class ApiServices
    {

        protected $accessName = "guest";


        public function __construct()
        {
            $this->accessName = isset($_ENV['__MP_ACCESS__']) ? $_ENV['__MP_ACCESS__'] : "guest";
        }

        public function __call($name, $arguments)
        {
            if (strtolower($name) === $name) {
                error_log("Calling ApiService is in Case Sensitive, Module may not found in linux");
            }
            return new ApiProxy($name);
        }
    }



    class ApiProxy
    {

        protected Module $module;

        public function __construct($module)
        {
            $this->module = Box::module($module);
        }


        public function __call($name, $arguments)
        {

            $output = null;
            $service = $this->module->Service;
            if ($service->__method_exists($name)) {
                if ($arguments && count($arguments) === 1 && is_array($arguments[0])) {
                    $arguments = $arguments[0];
                }
                $output = $service->$name(...$arguments);
            } else if ($this->module->$name instanceof Proxy) {
                $output = $this->module->$name;
            } else if (method_exists($this->module, $name)) {
                $output = $this->module->$name(...$arguments);
            } else {
                error_log("Method $name not found in {$this->module->namespace}");
            }

            return $output;
        }
    }
}