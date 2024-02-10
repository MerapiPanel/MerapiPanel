<?php

namespace MerapiPanel;

use MerapiPanel\Core\Cog\Config;
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




    public static function module($args)
    {


        if (!isset(self::$instance)) self::$instance = new Box();
        return self::$instance->callModule($args);
    }




    public function callModule($args)
    {

        if (is_array($args) && count($args) > 1) {

            $module = BoxModule::with($args[0]);
            $ouput = [];

            $arguments = $args[1];
            $methods = array_keys($arguments);

            foreach ($methods as $method) {

                try {
                    $ouput[$method] = $module->$method($arguments[$method]);
                } catch (\Exception $e) {
                    throw new \Exception($e->getMessage());
                }
            }
            return $ouput;
        }


        return BoxModule::with((is_string($args) ? $args : $args[0]));
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


    public function __call($name, $arguments)
    {
        $serviceInstance = $this->service();
        if ($name == "service") {
            $serviceInstance = $this->service($arguments[0]);
        }


        error_log("serviceInstance: " . $serviceInstance);
    }




    private function service($name = null)
    {

        $serviceClassName = $this->baseModule . "/service";

        if (!empty($name)) {

            if (class_exists("{$this->baseModule}\\$name")) {
                $serviceClassName = "{$this->baseModule}\\$name";
            } elseif (class_exists("{$this->baseModule}\\{$name}Service")) {
                $serviceClassName = "{$this->baseModule}\\{$name}Service";
            } else if (class_exists("{$this->baseModule}\\service{ucfirst($name)}")) {
                $serviceClassName = "{$this->baseModule}\\service{ucfirst($name)}";
            } else {
                throw new \Exception("Service $name not found");
            }
        }

        return $serviceClassName;
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
