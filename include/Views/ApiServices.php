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
            $output = null;
            $service = $this->module->Service;
            if ($service->__method_exists($name)) {
                if($arguments && count($arguments) === 1 && is_array($arguments[0])) {
                    $arguments = $arguments[0];
                }
                $output = $service->$name(...$arguments);
            } else if($this->module->$name instanceof Proxy) {
                $output = $this->module->$name;
            }
            
            return $output;
        }
    }
}