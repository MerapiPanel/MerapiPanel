<?php

namespace il4mb\Mpanel\Core;

use il4mb\Mpanel\Core\Mod\ModProxy;

class BoxMod extends Box
{

    protected $stack = [];
    protected Box $box;

    public function __construct()

    {
        $this->base = "il4mb\\Mpanel\\Modules";
    }

    public function setBox(Box $box)
    {
        $this->box = $box;
    }

    public function getBox(): ?Box
    {
        return $this->box;
    }

    function __call($name, $arguments)
    {

        $parts    = explode("_", $name);
        $name     = ucfirst($parts[0]);
        $class    = $this->base . "\\" . ucfirst(ltrim($name, "\\"));

        if(!class_exists($class)) {
            $class .= "\\Service";
        }

        if (!class_exists($class)) {
            throw new \Exception("Error: $class not found");
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

            $nested = new ModProxy($className);

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
