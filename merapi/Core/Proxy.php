<?php

namespace MerapiPanel\Core;

use Exception;
use MerapiPanel\Box;
use MerapiPanel\Core\Exception\MethodNotFoud;
use MerapiPanel\Core\Exception\MissingArgument;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use Symfony\Component\Yaml\Yaml;


final class Proxy
{

    protected Box $box;
    protected string $className;
    private ?object $instance = null;
    protected $args = [];
    protected $meta = [];


    function __construct($className, $arguments)
    {
        $this->className = $className;
        $this->args = $arguments;
    }


    public function setBox($box)
    {

        $this->box = $box;
        $this->instance = $this->initialInstance($this->className, $this->args);
    }

    public function getBox()
    {
        return $this->box;
    }



    public function initialInstance($className, $arguments = [])
    {

        $reflection = new ReflectionClass($className);
        $construct = $reflection->getConstructor();
        $instance = null;

        if ($construct) {

            $classParams = $construct->getParameters();
            $passedParams = [];

            foreach ($classParams as $key => $param) {

                $paramType = $param->getType();

                if (
                    $paramType && (ltrim($paramType, "?") == self::class ||
                        ltrim($paramType, "?") == $this::class
                    )
                ) {

                    throw new Exception("Not allowed to use " . self::class . " or " . $this::class . " in constructor");
                } else {

                    $paramName = $param->getName();
                    if (isset ($arguments[$paramName])) {

                        $passedParams[] = $arguments[$paramName];
                    } elseif (isset ($arguments[$key])) {

                        $passedParams[] = $arguments[$key];
                    } else {

                        if (count($arguments) === count($classParams)) {
                            $passedParams = $arguments;
                        } else {
                            throw new Exception("Missing argument: $paramName at key: $key");
                        }
                    }
                }
            }

            if ($reflection->getConstructor()->isPublic()) {
                if (!empty ($passedParams)) {
                    $instance = $reflection->newInstanceArgs($passedParams);
                } else {
                    $instance = $reflection->newInstance();
                }
            } else {
                $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
                foreach ($methods as $method) {
                    // Check if the method has a return type and it is an object or a specific class
                    if ($returnType = $method->getReturnType()) {

                        // Check if the return type is an object or a specific class name
                        // For generic object detection, PHP 7.2+ allows using 'object' as a return type
                        if (strtolower($returnType) === strtolower($className)) {
                            error_log($method->getName() . " return type: " . $returnType);
                            $instance = $method->invokeArgs($this->instance, $passedParams);
                        }
                    }
                }
            }
        } else {
            $instance = $reflection->newInstanceWithoutConstructor();
        }

        if (method_exists($instance, "setBox")) {
            call_user_func([$instance, "setBox"], $this->box);
        }

        $this->initMeta($reflection);

        return $instance;
    }






    public function getClassName()
    {
        return $this->className;
    }



    public function getProperty($name)
    {


        $reflectionClass = new ReflectionClass($this->className);
        if ($reflectionClass->hasProperty($name)) {
            $property = $reflectionClass->getProperty($name);
            $property->setAccessible(true);
            return $property->getValue($this->instance);
        } else {
            return null; // Property does not exist
        }
    }




    public function getRealInstance()
    {
        return $this->instance;
    }

    public function methodExists($name)
    {
        $reflectionClass = new ReflectionClass($this->className);
        if ($reflectionClass->hasMethod($name)) {
            return true;
        }
        return false;
    }



    public function __call($name, $arguments)
    {

        Box::Get($this)->getEvent()->notify(preg_replace("/(\\\|\\/|\s)+/im", ":", strtolower($this->className . ":" . $name)), $arguments);
        if (!$this->instance) {
            return null;
        }

        if (method_exists($this->instance, $name)) {
            $reflectionMethod = new ReflectionMethod($this->instance, $name);
            $parameters = $reflectionMethod->getParameters();

            if (count($parameters) > 0) {
                $invocationArgs = [];

                foreach ($parameters as $key => $param) {


                    if (isset ($arguments[$key])) {

                        $argument = $arguments[$key];

                        if ($param->hasType()) {
                            $paramType = $param->getType();
                            assert($paramType instanceof ReflectionNamedType);


                            // For class types, check if the argument is an instance of the parameter type                      
                            if (is_array($argument) || (is_object($argument) && strtolower(get_class($argument)) == strtolower($paramType->getName()))) {
                                // Attempt conversion or handling for class types here
                                // For now, we'll skip conversion and assume proper types are passed
                                $invocationArgs[] = $argument;
                            } else {
                                // Attempt to convert scalar types
                                $convertedArg = $this->convertScalarType($argument, $paramType->getName());
                                $invocationArgs[] = $convertedArg !== null ? $convertedArg : $argument;
                            }
                        } else {
                            // No type hint; use the argument as is
                            $invocationArgs[] = $argument;
                        }
                    } elseif ($param->isDefaultValueAvailable()) {

                        $invocationArgs[] = $param->getDefaultValue();

                    } else {
                        // Missing argument without a default value
                        throw new MissingArgument("Missing argument for parameter '$param->name' at position $key");
                    }
                }

                return $reflectionMethod->invokeArgs($this->instance, $invocationArgs);
            }

            return call_user_func_array([$this->instance, $name], $arguments);
        }

        throw new MethodNotFoud("Method $name not found in " . get_class($this->instance));
    }


    private function convertScalarType($value, $toType)
    {

        if ($value instanceof self) {
            return self::Real($value);
        }
        return null;
    }










    private function initMeta(ReflectionClass $reflection)
    {

        if (!isset ($this->instance)) {
            $this->createInstance($this->className);
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
            "name" => $modName ? $modName : ucfirst(basename($file)),
            "version" => "1.0.0",
            "author" => "Merapi panel",
            'image' => "/src/views/assets/img/default-box.svg",
            "location" => str_replace("\\", "/", $file),
            "file" => str_replace("\\", "/", $reflection->getFileName()),
        ];
        $this->meta = array_merge($this->meta, $meta);

        if (file_exists($yml)) {
            $array = Yaml::parseFile($yml);

            if (isset ($array["name"])) {
                $this->meta["name"] = $array["name"];
            }
            if (isset ($array["version"])) {
                $this->meta["version"] = $array["version"];
            }
            if (isset ($array["author"])) {
                $this->meta["author"] = $array["author"];
            }
            if (isset ($array["description"])) {
                $this->meta["description"] = $array["description"];
            }
            if (isset ($array["image"]) && !empty ($array["image"])) {
                $this->meta["image"] = $array["image"];
            }
        }
    }



    public function __reBuild(...$arguments)
    {
        $this->instance = $this->initialInstance($this->className, $arguments);
        return $this;
    }



    public static function Real(Proxy $proxy)
    {
        return $proxy->instance;
    }

    public static function fromObject($object)
    {

        $proxy = new self("", []);
        $proxy->className = get_class($object);
        $proxy->instance = $object;
        return $proxy;
    }


    public final function __toString()
    {

        if (method_exists($this->instance, "__toString")) {
            return $this->instance->__toString();
        }
        return "(Module)" . $this->className;
    }
}
