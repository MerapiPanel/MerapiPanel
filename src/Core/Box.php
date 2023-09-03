<?php

namespace il4mb\Mpanel\Core;

use ReflectionClass;

abstract class Box
{
    protected $base = "il4mb\\Mpanel";

    abstract function __call($address, $arguments);

    final function __register($class, &$stack)
    {

        if(!class_exists($class))
        {
            throw new \Exception("Error: $class not found");
        }

        $class    = strtolower(str_replace("\\", "_", ltrim(str_replace($this->base, "", $class), "\\")));
        $segments = explode("_", $class);

        $nested = &$stack;
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

        if (empty($nested) && class_exists($class)) {

            $classReflection  = new ReflectionClass($class);
            $classConstructor = $classReflection->getConstructor();

            if ($classConstructor !== null) {

                $classParams     = $classConstructor->getParameters();
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
            }


            if (!empty($passedParams)) {
                $nested = $classReflection->newInstanceArgs($passedParams);
            } else {
                $nested = $classReflection->newInstance();
            }

            /**
             * @var ReflectionClass $nested
             */
            $nested->{"__location__"} = $class;

            if (method_exists($nested, 'setBox')) {
                call_user_func(array($nested, 'setBox'), $this);
            }
        }
    }
}
