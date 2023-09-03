<?php

namespace il4mb\Mpanel\Core;

use il4mb\Mpanel\Core\Cog\Config;
use ReflectionClass;

class BoxApp extends Box
{


    protected bool $debug;
    protected $stack = [];
    protected Config $cog;

    public function __construct()
    {
        $this->base = "il4mb\\Mpanel";
    }



    final public function setConfig(string $fileYml)
    {

        $this->cog = new Config($fileYml);

        if (!isset($this->cog['debug'])) {
            throw new \Exception("Cofig error: debug not found, check config file the key 'debug' is missing, 'debug' is required with the value 'true' or 'false'");
        }
        if (!isset($this->cog['admin'])) {
            throw new \Exception("Cofig error: admin not found, check config file the key 'admin' is missing, 'admin' is url path to admin segment");
        }

        if (!isset($this->cog['services'])) {
            throw new \Exception("Cofig error: services not found, check config file the key 'services' is missing, 'services' is array of services");
        }
    }


    final public function getConfig(): Config
    {
        return $this->cog;
    }



    final public function __call($address, $arguments)
    {


        
        if (strpos($address, "_mod") === 0) {

            $object = $this->stack["modules"]['index']["controller"]["guest"];
            print_r(get_object_vars($object));

            throw new \Exception("Error: $address not found");
        }



        if(class_exists($address)) {
            $address = strtolower(str_replace("\\", "_", $address));
            $address = str_replace("il4mb_mpanel_", "", $address);
        }

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


        if (!class_exists($className)) {

            throw new \Exception("Error: $className not found");
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
            $nested->{"__location__"} = $className;

            if (method_exists($nested, 'setBox')) {
                call_user_func(array($nested, 'setBox'), $this);
            }
        }

        return $nested;
    }
}
