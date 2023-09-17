<?php

namespace Mp\Core\Mod;

use Mp\Box;
use ReflectionClass;
use Symfony\Component\Yaml\Yaml;

final class Proxy
{
    
    protected Box $box;
    protected string $classInstance;
    protected Object $instance;
    protected $meta = [];


    function __construct($classInstance, $arguments)
    {

        $this->classInstance = $classInstance;
        $this->meta['arguments'] = $arguments;
    }


    public function setBox($box)
    {
        $this->box = $box;
        $this->instance = $this->createInstance();
    }

    public function getBox()
    {
        return $this->box;
    }




    private function createInstance()
    {

        if (!class_exists($this->classInstance)) {

            throw new \Exception("Module " . $this->classInstance . " not found");
        } else {

            $reflection = new \ReflectionClass($this->classInstance);
            $construct = $reflection->getConstructor();
            if ($construct) {

                $classParams     = $construct->getParameters();
                $passedParams    = [];

                foreach ($classParams as $key => $param) {

                    $paramType = $param->getType();

                    if ($paramType && (ltrim($paramType, "?") == self::class ||
                        ltrim($paramType, "?") == $this::class
                    )) {

                        throw new \Exception("Not allowed to use " . self::class . " or " . $this::class . " in constructor");

                    } else {

                        $paramName = $param->getName();
                        if (isset($arguments[$paramName])) {

                            $passedParams[] = $arguments[$paramName];
                        } elseif (isset($arguments[$key])) {

                            $passedParams[] = $arguments[$key];
                        } else {


                            throw new \Exception("Missing argument: $paramName at key: $key");
                        }

                    }
                }

                if (!empty($passedParams)) {
                    $this->instance = $reflection->newInstanceArgs($passedParams);
                } else {
                    $this->instance = $reflection->newInstance();
                }
            } else {
                $this->instance = $reflection->newInstanceWithoutConstructor();
            }

            if (method_exists($this->instance, "setBox")) {
                call_user_func([$this->instance, "setBox"], $this->box);
            }

            $this->initMeta($reflection);

            return $this->instance;
        }
    }

    public function getRealInstance()
    {
        return $this->instance;
    }



    public function __call($name, $arguments)
    {

        if (method_exists($this->instance, $name)) {

            return call_user_func_array([$this->instance, $name], $arguments);
        }

        if (class_exists($this->classInstance . "\\$name")) {

            $className = $this->classInstance . "\\$name";

            $instance = new Proxy($className, $arguments);
            $instance->setBox($this->box);

            return $instance;
        }
    }


    private function initMeta(ReflectionClass $reflection)
    {

        if (!isset($this->instance)) {
            $this->createInstance($this->classInstance);
        }

        $file = $reflection->getFileName();
        $parts = explode((PHP_OS == "WINNT" ? "\\" : "/"), $file);

        $modName = null;
        foreach ($parts as $key => $part) {

            if (strtolower($part) == "module") {
                $modName = $parts[$key + 1];
                $file = implode("/", array_slice($parts, 0, $key + 1)) . "/" . $modName;
                break;
            }
        }

        $yml = $file . "/module.yml";

        $meta = [
            "name"     => $modName ? $modName : ucfirst(basename($file)),
            "version"  => "1.0.0",
            "author"   => "Merapi panel",
            "location" => $file
        ];
        $this->meta = array_merge($this->meta, $meta);

        if (file_exists($yml)) {
            $array = Yaml::parseFile($yml);

            if (isset($array["name"])) {
                $this->meta["name"] = $array["name"];
            }
            if (isset($array["version"])) {
                $this->meta["version"] = $array["version"];
            }
            if (isset($array["author"])) {
                $this->meta["author"] = $array["author"];
            }
            if (isset($array["description"])) {
                $this->meta["description"] = $array["description"];
            }
        }
    }


    public function __getMeta()
    {
        return $this->meta;
    }


    public final function __toString()
    {

        if (method_exists($this->instance, "__toString")) {
            return $this->instance->__toString();
        }
        return "(Module)" . $this->classInstance;
    }
}
