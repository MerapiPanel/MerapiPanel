<?php

namespace il4mb\Mpanel\Core\Mod;

use il4mb\Mpanel\Core\Box;
use Symfony\Component\Yaml\Yaml;
use Throwable;

final class Proxy
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
        $meta = $this->getDetails();

        try {

            return call_user_func([$this->instance, $name], ...$arguments);

        } catch (\Exception $e) {

            throw new \Exception("Module {$meta['name']} doesn't have method $name");

        } catch (\TypeError $e) {

            throw new \Exception("Type error: {$e->getMessage()}");
            
        }
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

        $meta = [
            "name" => ucfirst(basename($file)),
            "version" => "1.0.0",
            "author" => "Merapi panel",
        ];

        if (file_exists($yml)) {
            $array = Yaml::parseFile($yml);

            if (isset($array["name"])) {
                $meta["name"] = $array["name"];
            }
            if (isset($array["version"])) {
                $meta["version"] = $array["version"];
            }
            if (isset($array["author"])) {
                $meta["author"] = $array["author"];
            }
            if (isset($array["description"])) {
                $meta["description"] = $array["description"];
            }
        }

        return $meta;
    }



    public function __toString()
    {
        return "(Module)" . $this->classInstance;
    }
}
