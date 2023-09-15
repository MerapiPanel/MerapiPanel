<?php

namespace Mp\Core;

use Mp\Core\Cog\Config;
use Mp\Core\Error\CodeException;
use ReflectionClass;

class BoxApp extends Box
{

    protected $base = "Mp\\Core";
    protected bool $debug;
    protected $stack = [];
    protected Config $cog;


    final public function setConfig(string $fileYml)
    {

        $this->cog = new Config($fileYml);

        if (!isset($this->cog['debug'])) {
            throw new \Exception("Cofig error: debug not found, check config file the key 'debug' is missing, 'debug' is required with the value 'true' or 'false'");
        }
        if (!isset($this->cog['admin'])) {
            throw new \Exception("Cofig error: admin not found, check config file the key 'admin' is missing, 'admin' is url path to admin segment");
        }

        if (!isset($this->cog['service'])) {
            throw new \Exception("Cofig error: services not found, check config file the key 'services' is missing, 'services' is array of services");
        }
    }


    final public function getConfig(): Config
    {
        return $this->cog;
    }



    final public function __call($address, $arguments)
    {


        if(class_exists($address)) {
            $address = strtolower(str_replace("\\", "_", $address));
            $address = str_replace("il4mb_mpanel_", "", $address);
        }

        $segments = explode("_", $address);

        $nested = &$this->stack;
        $className = $this->base;


        foreach ($segments as $x => $key) {
            if(strpos($className, "\\" . ucfirst($key))) {
                unset($segments[$x]);
                
            } else {

                $segments = array_values($segments);
                break;
            }
        }
        

        foreach ($segments as $x => $key) {

            if (!isset($nested[$key])) {
                $nested[$key] = [];
            }
        
            if ($x < count($segments) - 1) {
                // Ensure that "entity" key is set if the next segment is not the last one
                if (is_object($nested[$key])) {
                    $nested[$key] = ["entity" => $nested[$key]];
                }
            }
      
            // Append the current segment to $className, ensuring there's no duplication
            if (strpos($className, "\\" . ucfirst($key)) === false) {
                $className .= "\\" . ucfirst($key);
            }

            $nested = &$nested[$key];
        }


        // before construction
        if (is_array($nested) && isset($nested["entity"]) && is_object($nested["entity"])) 
        {
            
            return $nested["entity"];
        }




        if (!class_exists($className)) {
            $className .= "\\Entity";
        }


        if (!class_exists($className)) {

            $e = new CodeException("Error: Module <b>" .  $address . "</b> not found");
            $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2); // Get the call stack

            if (isset($trace[0]['file'])) {
                $e->setFile($trace[0]['file']); // Set the file from the caller
            }
            if(isset($trace[0]['line'])) {
                $e->setLine($trace[0]['line']); // Set the line from the caller
            }

            throw $e;
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


        // after construction
        if (is_array($nested) && isset($nested["entity"]) && is_object($nested["entity"])) {
            return $nested["entity"];
        }

        return $nested;
    }
}
