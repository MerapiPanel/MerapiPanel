<?php

namespace MerapiPanel;

use MerapiPanel\Core\Cog\Config;
use MerapiPanel\Core\Mod\Proxy;

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


    final public function getEvent()
    {
        if (!isset($this->event)) $this->event = new Event();
        return $this->event;
    }


    public function __registerController()
    {

        // Directory where your PHP files are located
        $directory = realpath(__DIR__ . "/Module"); // You may need to specify your project's directory here

        // Get a list of all PHP files in the directory
        $phpFiles = glob($directory . '/*');

        $namespacePattern = __NAMESPACE__ . '\\Module\\';
        $controllers = [];

        $zones = ['guest'];
        if ($this->Module_Auth()->isAdmin()) {
            $zones[] = 'admin';
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
            $object->register($this->utility_router()->getRealInstance());
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
