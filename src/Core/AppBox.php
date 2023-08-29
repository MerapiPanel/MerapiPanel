<?php

namespace il4mb\Mpanel\Core;

use Exception;
use il4mb\Mpanel\Core\Exception\CodeException;
use il4mb\Mpanel\Core\Exception\ConstructorException;
use ReflectionClass;

class AppBox
{

    public function register(mixed $class, ...$args)
    {

        if (is_object($class)) {

            $instance = $class;
            $class    = get_class($class);
        }

        if (strpos(__NAMESPACE__, $class) === 0) {

            throw new Exception("You can`t register " . $class . " instance");
        }



        try {

            $classNames      = array_values(array_filter(explode('\\', strtolower(substr($class, strlen(__NAMESPACE__))))));

            if (!isset($instance)) {

                if (!class_exists($class)) {

                    throw new Exception("Class $class does not exist");
                } else {

                    $reflectionClass = new ReflectionClass($class);
                    $constructor     = $reflectionClass->getConstructor();

                    if ($constructor) {

                        $constructorParams = $constructor->getParameters();
                        $resolvedArgs      = [];

                        foreach ($constructorParams as $param) {

                            $paramName = $param->getName();
                            $paramType = $param->getType();

                            if (ltrim($paramType, "\?") === Container::class || ltrim($paramType, "\?") === $this::class) {

                                throw new Exception("Object container can`t be used as argument for constructor\nYou can retrieve container using method getContainer()\nexample: setContainer(?Container \$container) in class $class");
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
                }
            }

            /**
             * @var Object $instance
             */
            if (isset($instance)) {

                $index = $classNames[0];
                unset($classNames[0]);

                $stack = [];
                $nestedArray = &$stack;
                foreach ($classNames as $value) {

                    $nestedArray[$value] = [];

                    if ($value == strtolower(basename(get_class($instance)))) {
                        $nestedArray[$value] = $instance;
                    }

                    $nestedArray = &$nestedArray[$value];
                }

                $this->$index = $stack;


                if (method_exists($instance, 'setBox')) {
                    $instance->setBox($this);
                }
            }
        } catch (Exception $e) {

            if (isset($reflectionClass)) {

                $except = new CodeException($e->getMessage(), 0, $e);
                $except->setFile($reflectionClass->getFileName());
                $except->setLine($reflectionClass->getConstructor()->getStartLine());


                throw $except;
            }
            throw $e;
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

        return $stack;
    }
}
