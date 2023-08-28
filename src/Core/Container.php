<?php

namespace il4mb\Mpanel\Core;

use Exception;
use ReflectionClass;

class Container
{

    public function register(string $class, ...$args)
    {
        if (strpos(__NAMESPACE__, $class) === 0) {

            throw new Exception("You can`t register " . $class . " instance");
        }


        if (class_exists($class)) {

            $classNames      = array_values(array_filter(explode('\\', strtolower(substr($class, strlen(__NAMESPACE__))))));
            $reflectionClass = new ReflectionClass($class);
            $constructor     = $reflectionClass->getConstructor();

            if ($constructor) {

                $constructorParams = $constructor->getParameters();
                $resolvedArgs      = [];

                foreach ($constructorParams as $param) {

                    $paramName = $param->getName();
                    $paramType = $param->getType();

                    if (ltrim($paramType, "\?") === Container::class || ltrim($paramType, "\?") === $this::class) {

                        $resolvedArgs[] = $this;
                    } else {

                        if (isset($args[$paramName])) {
                            $resolvedArgs[] = $args[$paramName];
                        } else {
                            throw new Exception("Missing argument '$paramName' for class '$class'");
                        }
                    }
                }

                $instance = $reflectionClass->newInstanceArgs($resolvedArgs);
            } else {

                $instance = $reflectionClass->newInstanceWithoutConstructor();
            }

            /**
             * @var Object $instance
             */
            if (isset($instance)) {

                $index = $classNames[0];
                unset($classNames[0]);

                $stack = [];
                $nestedArray = &$stack;
                foreach ($classNames as $value) 
                {

                    $nestedArray[$value] = [];

                    if ($value == strtolower(basename(get_class($instance)))) 
                    {
                        $nestedArray[$value] = $instance;
                    }

                    $nestedArray = &$nestedArray[$value];
                }

               // print_r($stack);
                $this->$index = $stack;
            }
        } else {
            throw new Exception("Class $class does not exist");
        }
    }

    public function __invoke(...$args)
    {
        $indentify = false;
        $stack     = [];
        foreach ($args as $arg) {

            if (is_array($arg) || is_object($arg)) {

                $key        = $arg[0];
                $value      = $arg[1];
                $this->$key = $value;
            } elseif (is_string($arg)) {

                if (isset($this->$arg) && empty($stack)) {

                    $indentify = ucfirst($arg);
                    $stack     = $this->$arg;
                } elseif (isset($stack[$arg])) {

                    $indentify .= "/" . ucfirst($arg);
                    $stack      = $stack[$arg];
                } else {
                    if (empty($stack)) {

                        throw new Exception("$arg does not exist in Container");
                    }

                    throw new Exception("$indentify does not contain $arg");
                }
            }
        }

       // print_r($stack);

        return $stack;
    }


    public function __call($name, $arguments)
    {

        if (property_exists($this, $name)) {

            return $this->$name;
        }

        throw new Exception("Instance $name does not exist");
    }


    function get_list()
    {
        return get_object_vars($this);
    }
}
