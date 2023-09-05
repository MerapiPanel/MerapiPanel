<?php

namespace il4mb\Mpanel\Core\Mod;

use il4mb\Mpanel\Core\Box;
use ReflectionClass;
use Symfony\Component\Yaml\Yaml;

final class ModProxy
{

    protected string $classInstance;
    protected Box $box;
    protected $instance;
    protected $reflection;

    function __construct($classInstance)
    {
        $this->classInstance = $classInstance;
    }

    public function setBox($box)
    {

        $this->box = $box;
        $this->createInstance($this->classInstance);
    }

    private function createInstance($classInstance = null)
    {

        if (!class_exists($classInstance)) {

            throw new \Exception("Module $classInstance not found");
        } else {

            $this->reflection = new \ReflectionClass($classInstance);
            $this->instance = $this->reflection->newInstanceWithoutConstructor();

            if (method_exists($this->instance, "setBox")) {
                call_user_func([$this->instance, "setBox"], $this->box);
            }

            return $this->instance;
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

    public function getDetails()
    {

        if (!isset($this->instance)) {
            $this->createInstance($this->classInstance);
        }

        $file = $this->reflection->getFileName();

        while ($directory = dirname($file)) {
            if (basename($directory) === 'modules') {
                break;
            }
            $file = $directory;
        }

        $yml = $file . "/module.yml";

        if (!file_exists($yml)) {
            return null;
        }
        $meta = Yaml::parseFile($yml);

        return $meta;
    }
    public function __toString()
    {
        return "(Module)" . $this->classInstance;
    }
}
