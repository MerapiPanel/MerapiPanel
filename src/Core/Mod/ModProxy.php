<?php

namespace il4mb\Mpanel\Core\Mod;

use il4mb\Mpanel\Core\Box;
use il4mb\Mpanel\Core\BoxMod;

final class ModProxy
{

    protected string $base;
    protected Box $box;
    protected $modService;

    function __construct($classService)
    {
        $this->base = pathinfo($classService, PATHINFO_DIRNAME);
    }

    public function setBox($box)
    {
        $this->box = $box;

        $this->modService = $this->createReflection();
    }


    private function service()
    {
    }

    private function createReflection($segment = "service")
    {
        $class = $this->base . "\\" . ucfirst(ltrim($segment, "\\"));
        $reflection = new \ReflectionClass($class);
        $object = $reflection->newInstanceWithoutConstructor();

        if (method_exists($object, "setBox")) {
            call_user_func([$object, "setBox"], $this->box);
        }
        return $object;
    }

    public function __call($name, $arguments)
    {
      //  if(method_exists($this->modService, $name)){
            return call_user_func_array([$this->modService, $name], $arguments);
        //} else {
          //  throw new \Exception("Method $name not found");
        //}
    }

    private function createSegment($command)
    {
        if (!strpos($command, "_")) {
            throw new \Exception("underscore is required in method name");
        }

        $segments = explode("_", $command);

        $segment = [
            "segment" => (string) $this->box->getBox()->core_segment(),
            "entity"  => "",
            "method"  => ""
        ];

        $segment["entity"]  = ucfirst($segments[0]);
        unset($segments[0]);
        $segment["method"] = implode("_", $segments);


        return $segment;
    }
}
