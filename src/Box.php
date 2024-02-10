<?php

namespace MerapiPanel;

use MerapiPanel\Core\Cog\Config;
use MerapiPanel\Core\Exception\ServiceNotFound;
use MerapiPanel\Core\Mod\Proxy;

/**
 * Description: Box is an instance used for communication between instances in MerapiPanel, especially for modules. With a box, it allows for communication between modules.
 *
 * For more information, see the Class Box at https://github.com/MerapiPanel/MerapiPanel/wiki/Class-Box.
 *
 * @author      ilham b <durianbohong@gmail.com>
 * @copyright   Copyright (c) 2022 MerapiPanel
 * @license     https://github.com/MerapiPanel/MerapiPanel/blob/main/LICENSE
 * @lastUpdate  2024-02-10
 */

class Box
{


    protected bool $debug;
    protected $stack = [];
    protected Config $cog;
    protected Event $event;
    private static Box $instance;


    public static function Get(Object $object): Box
    {
        if (!isset(self::$instance)) self::$instance = new Box();
        return self::$instance;
    }




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

        $GLOBALS['config'] = $this->cog;
    }




    final public function getConfig(): Config
    {

        return $this->cog;
    }




    final public function __call($name, $arguments)
    {

        if (strtolower($name) == "module") return $this->callModule($arguments);


        // assign current instance to sure that not null
        if (!isset(self::$instance)) self::$instance = $this;

        $name = str_replace(__NAMESPACE__, "", $name);
        $name = strtolower(str_replace("\\", "_", str_replace("/", "_", $name)));
        $name = ltrim(str_replace(strtolower(trim(__NAMESPACE__)) . "_", "", $name), "_");

        $className = __NAMESPACE__ . "\\" . implode("\\", array_map("ucfirst", explode("_", $name)));
        if (!class_exists($className)) $className .= "\\Service";
        $theKey = strtolower(preg_replace("/[^A-Za-z0-9]+/", "", $className));

        $instance = &$this->stack;
        if (isset($instance[$theKey])) {
            return $instance[$theKey];
        }

        $instance = &$instance[$theKey];

        if (class_exists($className)) {

            $instance = new Proxy($className, $arguments);

            if (method_exists($instance, "setBox")) {
                call_user_func([$instance, "setBox"], $this);
            }
        }

        return $instance;
    }




    public static function module(string $moduleName, array $args = [])
    {
        if (!isset(self::$instance)) self::$instance = new Box();

        error_log("begin call module: " . $moduleName);
        return self::$instance->callModule($moduleName, $args);
    }




    public function callModule(string $moduleName, array $args = [])
    {

        error_log("start call module: " . $moduleName);

        if (is_array($args) && count($args) > 0) {

            $module = BoxModule::with($moduleName);
            $ouput = [];

            $methods = array_keys($args);

            foreach ($methods as $method) {

                try {
                    // call method without argument
                    if (is_numeric($method) && is_string($args[$method])) {

                        $method = $args[$method];
                        $ouput[$method] = $module->$method();

                        error_log("call module " . $moduleName . " method: " . $method);
                    }
                    // call method with argument 
                    elseif (is_string($method) && is_array($args[$method])) {

                        $arguments = is_array($args[$method]) ? $args[$method] : [$args[$method]];
                        $ouput[$method] = $module->$method(...$arguments);

                        error_log("call module " . $moduleName . " method: " . $method . " with args: " . json_encode($arguments));
                    }
                } catch (\Exception $e) {

                    throw new \Exception($e->getMessage());
                }
            }
            return $ouput;
        }


        error_log("call module without call any method");


        return BoxModule::with($moduleName);
    }




    final static function __callStatic($name, $arguments)
    {
        if (!isset(self::$instance)) self::$instance = new Box();
        return self::$instance->__call($name, $arguments);
    }




    public function getCaller()
    {

        $call = null;
        foreach (debug_backtrace() as $trace) {
            if (isset($trace['file']) && realpath($trace['file']) != realpath(__FILE__)) {
                $call = $trace['file'];
            }
        }

        return $call;
    }




    final public function getEvent()
    {
        if (!isset($this->event)) $this->event = new Event();
        return $this->event;
    }




    public function __registerController()
    {

        // Directory where your PHP files are located
        $directory = realpath(__DIR__ . "/module"); // You may need to specify your project's directory here

        // Get a list of all PHP files in the directory
        $phpFiles = glob($directory . '/*');

        $namespacePattern = __NAMESPACE__ . '\\module\\';
        $controllers = [];

        $zones = ['guest'];
        if ($this->Module_Auth()->isAdmin()) {
            $zones[] = 'admin';
            error_log("section: admin");
        }

        foreach ($phpFiles as $file) {

            $mod = basename($file);

            foreach ($zones as $zone) {

                $className = $namespacePattern . ucfirst($mod) . "\\Controller\\" . ucfirst($zone);

                if (class_exists($className)) {
                    $controllers[] = [
                        "name" => $mod,
                        "class" => $className
                    ];
                }
            }
        }

        foreach ($controllers as $controller) {

            $class  = $controller["class"];
            $object = $this->$class();
            $object->register(Proxy::Real($this->utility_router()));
        }
    }



    public function __getZone()
    {

        /**
         * Do verification here
         */

        setcookie('auth', 'admin', time() + 3600, "/");

        return isset($_COOKIE['auth']) ? "admin" : "guest";
    }
}





class BoxModule
{

    private $baseModule;
    public function __construct($baseModule)
    {
        $this->baseModule = $baseModule;
    }


    public function __call($name, $args)
    {

        $className = $this->findServiceClassName();

        if ($name == "service") {

            if (isset($args[0]) && is_string($args[0])) {
                $className = $this->findServiceClassName($args[0]);
            } else {
                throw new ServiceNotFound("The name of the service is required");
            }
        }

        error_log("call module: " . $this->getModuleName() . " method:" . $name . " with args: " . json_encode($args));


        $proxy = Box::$className();

        if (isset($args[1]) && is_array($args[1])) {

            $arguments = $args[1];
            $methods = array_keys($arguments);

            $ouput = [];
            foreach ($methods as $method) {
                $ouput[$method] = $this->call($proxy, $method, $arguments[$method]);
            }
            return $ouput;
        }

        return $proxy->$name(...$args);
    }


    private function call(Proxy $service, string $method, array $args = [])
    {

        try {
            return $service->$method(...$args);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }



    private function findServiceClassName($name = null)
    {

        $serviceClassName = $this->baseModule . "/service";

        if (!empty($name) && is_string($name)) {

            if (class_exists("{$this->baseModule}\\$name")) {
                $serviceClassName = "{$this->baseModule}\\$name";
            } elseif (class_exists("{$this->baseModule}\\{$name}Service")) {
                $serviceClassName = "{$this->baseModule}\\{$name}Service";
            } else if (class_exists("{$this->baseModule}\\service{ucfirst($name)}")) {
                $serviceClassName = "{$this->baseModule}\\service{ucfirst($name)}";
            } else {
                throw new ServiceNotFound("Service $name not found");
            }
        }

        return $serviceClassName;
    }


    public function getModuleName()
    {

        return ucfirst(basename($this->baseModule));
    }


    public static function findModuleBaseClassName($moduleName)
    {

        $path = realpath(__DIR__ . "/module/" . $moduleName);
        if (!file_exists($path)) {
            throw new \Exception("Module $moduleName not found");
        }

        return "\\MerapiPanel\\module\\{$moduleName}";
    }


    public static function with($moduleName)
    {
        return new self(self::findModuleBaseClassName($moduleName));
    }
}
