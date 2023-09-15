<?php

namespace Mp\Core;

use Mp\Core\Mod\Proxy;

class BoxMod extends Box
{

    protected $stack = [];
    protected Box $box;


    public function __construct()
    {
        $this->base = "Mp\\Modules";
    }


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


                throw new \Exception("Error: Module " . $name . " not found");
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
            if (isset($nested->getDetails()['location'])) {
                $view = $this->box->view();
                $viewLoader = $view->getLoader();
                $viewLoader->addPath($nested->getDetails()['location'], $nested->getDetails()['name']);
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
