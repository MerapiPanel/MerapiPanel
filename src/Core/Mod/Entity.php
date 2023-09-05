<?php

namespace il4mb\Mpanel\Core\Mod;

use il4mb\Mpanel\Core\Box;
use il4mb\Mpanel\Core\Error\CodeException;

class Entity extends Box
{

    protected $base = "il4mb\\Mpanel\\Modules";
    protected $stack = [];
    protected Box $box;

    public function setBox(Box $box)
    {
        $this->box = $box;
        $this->box->core_mod_factory();
    }


    function __call($name, $arguments)
    {

        if (!class_exists($name)) {

            $parts    = explode("_", $name);
            $name     = ucfirst($parts[0]);
            $class    = $this->base . "\\" . ucfirst(ltrim($name, "\\"));

            if (!class_exists($class)) {
                $class .= "\\Service";
            }

            if (!class_exists($class)) {


                $e = new CodeException("Error: Module <b>" .  $name . "</b> not found");
                $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2); // Get the call stack

                if (isset($trace[0]['file'])) {
                    $e->setFile($trace[0]['file']); // Set the file from the caller
                }
                if(isset($trace[0]['line'])) {
                    $e->setLine($trace[0]['line']); // Set the line from the caller
                }

                throw $e;
            }
        } else {

            $class = $name;
        }

        $class    = strtolower(str_replace("\\", "_", ltrim(str_replace($this->base, "", $class), "\\")));
        $segments = explode("_", $class);

        $nested    = &$this->stack;
        $className = $this->base;


        foreach ($segments as $x => $key) {

            if (!isset($nested[$key])) {
                $nested[$key] = [];
            }
            $className .= "\\" . ucfirst($key);

            if (is_object($nested[$key]) && $x < count($segments) - 1) {
                $nested[$key] = ["entity" => $nested[$key]];
            }
            $nested = &$nested[$key];
        }


        if (empty($nested)) {

            $nested = new Proxy($className);

            if (method_exists($nested, "setBox")) {
                call_user_func([$nested, "setBox"], $this);
            }
        }

        if (isset($parts[1])) {

            unset($parts[0]);
            $method = implode("_", $parts);

            return call_user_func([$nested, $method]);
        }

        return $nested;
    }
}
