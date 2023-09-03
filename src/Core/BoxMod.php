<?php

namespace il4mb\Mpanel\Core;

use il4mb\Mpanel\Core\Mod\ModProxy;

class BoxMod extends Box
{

    protected $stack = [];
  
    public function __construct()

    {
        $this->base = "il4mb\\Mpanel\\Modules";
    }

    function __call($name, $arguments)
    {

        $class = $this->base . "\\" . ltrim($name, "\\"). "\\Service";

        if(!class_exists($class))
        {
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
            $nested = new ModProxy();
        }

        return $nested;
        
    }
}
