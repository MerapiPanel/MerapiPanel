<?php

namespace MerapiPanel\Views {

    use MerapiPanel\Box;
    use MerapiPanel\Box\Module\Entity\Module;
    use MerapiPanel\Box\Module\Entity\Proxy;

    class BoxViewApi
    {

        protected $accessName = "guest";
        public function __construct()
        {
            $this->accessName = $_ENV['__MP_ACCESS__'] ?? "guest";
        }

        public function __call($name, $arguments)
        {
            if (strtolower($name) === $name) {
                error_log("Calling ApiService is in Case Sensitive, Module may not found in linux");
            }
            return new BoxViewProxy($name);
        }
    }


    class BoxViewProxy
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
                $output = new ModuleViewProxy($this->module->$name);
            } else if (method_exists($this->module, $name)) {
                $output = $this->module->$name(...$arguments);
            } else {
                error_log("Method $name not found in {$this->module->namespace}");
            }

            return $output;
        }
    }


    class ModuleViewProxy
    {

        protected Proxy $proxy;

        function __construct(Proxy $proxy)
        {
            $this->proxy = $proxy;
        }
        public function __call($name, $arguments)
        {

            $output  = null;

            if ($this->proxy->$name instanceof Proxy) {
                return new ModuleViewProxy($this->proxy->$name);
            } else if ($this->proxy->__method_exists($name)) {

                if ($arguments && count($arguments) == 1 && is_array($arguments[0])) {
                    $arguments = $arguments[0];
                }
                $output = $this->proxy->$name(...$arguments);
            } else if (method_exists($this->proxy, $name)) {
                $output = $this->proxy->$name(...$arguments);
            } else {
                error_log("Method $name not found in {$this->proxy->namespace}");
            }

            return $output;
        }
    }
}
