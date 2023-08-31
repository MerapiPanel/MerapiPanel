<?php

namespace il4mb\Mpanel\Core;

use ReflectionClass;

class AppBox
{


    protected $stack = [];
    protected Config $cog;



    final public function setConfig(string $fileYml)
    {
        $this->cog = new Config($fileYml);
        $services = $this->cog->get("services");

        if ($services !== null) {
            foreach ($services as $service) {
                $this->register($service);
            }
        }
    }



    final public function getConfig(): Config
    {
        return $this->cog;
    }



    final public function __call($address, $arguments)
    {
        $segments = explode("_", $address);

        $nested = &$this->stack;
        $className = "il4mb\\Mpanel";

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


        if (is_array($nested) && isset($nested["entity"]) && is_object($nested["entity"])) {
            return $nested["entity"];
        }


        if (!class_exists($className)) {
            $className .= "\\Entity";
        }


        if (empty($nested) && class_exists($className)) {

            $classReflection  = new ReflectionClass($className);
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

                        } elseif(isset($arguments[$key]))  {

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


            if (method_exists($nested, 'setBox')) {
                call_user_func(array($nested, 'setBox'), $this);
            }
        }

        return $nested;
    }
}
