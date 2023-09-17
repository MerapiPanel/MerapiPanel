<?php

namespace Mp;

use Mp\Core\Cog\Config;
use Mp\Core\Mod\Proxy;

class Box
{
    protected bool $debug;
    protected $stack = [];
    protected Config $cog;


    final public function setConfig(string $fileYml)
    {

        $this->cog = new Config($fileYml);

        if (!isset($this->cog['debug'])) {
            throw new \Exception("Cofig error: debug not found, check config file the key 'debug' is missing, 'debug' is required with the value 'true' or 'false'");
        }
        if (!isset($this->cog['admin'])) {
            throw new \Exception("Cofig error: admin not found, check config file the key 'admin' is missing, 'admin' is url path to admin segment");
        }

        if (!isset($this->cog['service'])) {
            throw new \Exception("Cofig error: services not found, check config file the key 'services' is missing, 'services' is array of services");
        }
    }


    final public function getConfig(): Config
    {
        return $this->cog;
    }


    final public function __call($name, $arguments)
    {

        $name = str_replace(__NAMESPACE__, "", $name);
        $name = strtolower(str_replace("\\", "_", str_replace("/", "_", $name)));
        $name = ltrim(str_replace(strtolower(trim(__NAMESPACE__)) . "_", "", $name), "_");

        $theClass = __NAMESPACE__ . "\\" . implode("\\", array_map("ucfirst", explode("_", $name)));
        $className = $theClass;

        if(!class_exists($className)) {

            $className .= "\\Service";
        }

        $instance = &$this->stack;
        if(isset($instance[$theClass])) {
           return $instance[$theClass];
        }

        $instance = &$instance[$theClass];

        if (class_exists($className)) {

            $instance = new Proxy($className, $arguments);

            if (method_exists($instance, "setBox")) {
                call_user_func([$instance, "setBox"], $this);
            }
        }

        return $instance;
    }



    public function __registerController()
    {

        // Directory where your PHP files are located
        $directory = realpath(__DIR__ . "/Module"); // You may need to specify your project's directory here

        // Get a list of all PHP files in the directory
        $phpFiles = glob($directory . '/*');

        $namespacePattern = 'Mp\\Module\\';
        $controllers = [];

        foreach ($phpFiles as $file) {

            $mod = basename($file);
            $className = $namespacePattern . ucfirst($mod) . "\\Controller\\" . ucfirst((string)$this->module_segment());

            if (class_exists($className)) {
                $controllers[] = [
                    "name" => $mod,
                    "class" => $className
                ];
            }
        }

        foreach ($controllers as $controller) {

            $class  = $controller["class"];
            $object = $this->$class();
            $object->register($this->utility_router());
        }
    }

    public function __getZone() {

        /**
         * Do verification here
         */

        return isset($_COOKIE['auth']) ? "admin" : "guest";
    }
}
