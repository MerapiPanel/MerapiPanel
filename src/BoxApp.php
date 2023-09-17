<?php

namespace Mp;

use Mp\Core\Cog\Config;
use Mp\Core\Exception\CodeException;
use Mp\Core\Mod\Proxy;


class BoxApp extends Box
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

        if (class_exists($name)) {

            $name = str_replace(__NAMESPACE__, "", $name);
            $name = strtolower(str_replace("\\", "_", $name));
            $name = str_replace(strtolower(trim(__NAMESPACE__)) . "_", "", $name);
        }


        $className = __NAMESPACE__;
        $nested = &$this->stack;
        $segments = explode("_", $name);

        foreach ($segments as $x => $key) {

            if (strpos($className, "\\" . ucfirst($key))) {

                unset($segments[$x]);
            } else {

                $segments = array_values($segments);
                break;
            }
        }

        $segments = array_filter($segments);

        foreach ($segments as $x => $key) {

            if (!isset($nested[$key])) {
                $nested[$key] = [];
            }

            if ($x < count($segments) - 1) {
                // Ensure that "entity" key is set if the next segment is not the last one
                if (is_object($nested[$key])) {
                    $nested[$key] = ["service" => $nested[$key]];
                }
            }

            // Append the current segment to $className, ensuring there's no duplication
            if (strpos($className, "\\" . ucfirst($key)) === false) {
                $className .= "\\" . ucfirst($key);
            }

            $nested = &$nested[$key];
        }


        // before construction
        if (is_object($nested)) {
            return $nested;
        }
        if (is_array($nested) && isset($nested["service"]) && is_object($nested["service"])) {

            return $nested["service"];
        }


        if (!class_exists($className)) {

            $className .= "\\Service";

            if (class_exists($className) && is_array($nested)) {

                $nested["service"] = null;
                $nested = &$nested["service"];
            }
        }


        if (!class_exists($className)) {

            $e = new CodeException("Error: Module <b>" .  ucfirst($name) . "</b> not contains the class: <b>" . basename(ltrim($className, "\\")) . "</b>");

            if (!file_exists(__DIR__ . "/Module/" . basename(ltrim($className, "\\")) . ".php")) {

                $e->setMessage("Error: Module <b>" .  ucfirst($name) . "</b> not found");
            }
            $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2); // Get the call stack

            if (isset($trace[0]['file'])) {
                $e->setFile($trace[0]['file']); // Set the file from the caller
            }
            if (isset($trace[0]['line'])) {
                $e->setLine($trace[0]['line']); // Set the line from the caller
            }

            throw $e;
        }


        if (class_exists($className)) {

            $nested = new Proxy($className, $arguments);

            if (method_exists($nested, "setBox")) {
                call_user_func([$nested, "setBox"], $this);
            }
        }

        // after construction
        if (is_array($nested) && (isset($nested["service"]) && is_object($nested["service"]))) {

            return $nested["service"];
        }

        return $nested;
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
}
