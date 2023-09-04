<?php

namespace il4mb\Mpanel\Core\Mod;

use il4mb\Mpanel\Core\Box;
use il4mb\Mpanel\Core\BoxMod;

final class ModProxy
{

    protected string $classInstance;
    protected Box $box;
    protected $instance;

    function __construct($classInstance)
    {
        $this->classInstance = $classInstance;
    }

    public function setBox($box)
    {

        $this->box = $box;
        $this->instance = $this->createInstance($this->classInstance);
        
    }

    private function createInstance($classInstance = null)
    {

        if (!class_exists($classInstance)) {

            throw new \Exception("Module $classInstance not found");

        } else {

            $reflection = new \ReflectionClass($classInstance);
            $object = $reflection->newInstanceWithoutConstructor();

            if (method_exists($object, "setBox")) {
                call_user_func([$object, "setBox"], $this->box);
            }
            return $object;
        }
    }

    public function __call($name, $arguments)
    {

        return call_user_func([$this->instance, $name], $arguments);

    }

    public function isMethodExists($name)
    {
        return method_exists($this->instance, $name);
    }

    public function __toString()
    {
        return "(Module)" . $this->classInstance;
    }
}
