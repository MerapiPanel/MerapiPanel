<?php

namespace MerapiPanel\Core\Mod;

use Exception;
use MerapiPanel\Box;
use MerapiPanel\Core\Exception\CodeException;
use MerapiPanel\Core\Exception\MethodNotFoud;
use MerapiPanel\Core\Exception\MissingArgument;
use MerapiPanel\Utility\Util;
use Reflection;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use Symfony\Component\Yaml\Yaml;
use  MerapiPanel\Core\Mod\Cache\Cache;

final class Proxy
{

    protected Box $box;
    protected string $className;
    private ?Object $instance = null;
    protected $args = [];
    protected $identify;
    protected $meta = [];
    // protected $reflection;


    function __construct($className, $arguments)
    {

        $this->identify = Cache::getIdentify($className);
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



    // public function initial()
    // {

    //     if (Cache::isExist($this->identify)) {
    //         $className = Cache::get($this->identify);
    //     }

    //     // $reflection = new \ReflectionClass($this->className);
    //     // $classDefinitionString = $this->createStringInstance($reflection);

    //     $className = Cache::set($this->className, $this->identify);

    //     return $this->initialInstance($className, $this->args);

    //     // $construct  = $reflection->getConstructor();

    //     // if ($construct) {

    //     //     $classParams     = $construct->getParameters();
    //     //     $passedParams    = [];

    //     //     foreach ($classParams as $key => $param) {

    //     //         $paramType = $param->getType();

    //     //         if ($paramType && (ltrim($paramType, "?") == self::class ||
    //     //             ltrim($paramType, "?") == $this::class
    //     //         )) {

    //     //             throw new \Exception("Not allowed to use " . self::class . " or " . $this::class . " in constructor");
    //     //         } else {

    //     //             $paramName = $param->getName();
    //     //             if (isset($arguments[$paramName])) {

    //     //                 $passedParams[] = $arguments[$paramName];
    //     //             } elseif (isset($arguments[$key])) {

    //     //                 $passedParams[] = $arguments[$key];
    //     //             } else {

    //     //                 if (count($this->meta['arguments']) === count($classParams)) {
    //     //                     $passedParams = $this->meta['arguments'];
    //     //                 } else {
    //     //                     throw new \Exception("Missing argument: $paramName at key: $key");
    //     //                 }
    //     //             }
    //     //         }
    //     //     }

    //     //     if (!empty($passedParams)) {
    //     //         $instance = $reflection->newInstanceArgs($passedParams);
    //     //     } else {
    //     //         $instance = $reflection->newInstance();
    //     //     }
    //     // } else {
    //     //     $instance = $reflection->newInstanceWithoutConstructor();
    //     // }

    //     // if (method_exists($instance, "setBox")) {
    //     //     call_user_func([$instance, "setBox"], $this->box);
    //     // }

    //     // $this->initMeta($reflection);

    //     // return $instance;
    // }


    public function initialInstance($className, $arguments = [])
    {

        $reflection = new \ReflectionClass($className);
        $construct  = $reflection->getConstructor();
        $instance = null;

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

                        if (count($arguments) === count($classParams)) {
                            $passedParams = $arguments;
                        } else {
                            throw new \Exception("Missing argument: $paramName at key: $key");
                        }
                    }
                }
            }

            if (!empty($passedParams)) {
                $instance = $reflection->newInstanceArgs($passedParams);
            } else {
                $instance = $reflection->newInstance();
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





    // public function createStringInstance(ReflectionClass $reflector)
    // {
    //     $classDefinitionString = $this->getIntierCode($reflector);

    //     // Regular expression to remove parameter types
    //     $pattern = '/\bfunction\s+\w+\s*\(([^)]*)\)/';
    //     preg_match_all($pattern, $classDefinitionString, $matches, PREG_SET_ORDER);

    //     foreach ($matches as $match) {
    //         // Adjusted to match optional nullable indicator `?` before type
    //         $modifiedParameters = preg_replace('/\??\w+\s+\&?\$/', '$', $match[1]);
    //         $newFunctionDef = str_replace($match[1], $modifiedParameters, $match[0]);
    //         $classDefinitionString = str_replace($match[0], $newFunctionDef, $classDefinitionString);
    //     }

    //     $modifiedClassName = "proxy_" . Util::uniq();
    //     $classDefinitionString = preg_replace('/\bclass\s+' . ($$reflector->getShortName()) . '\b/', "class $modifiedClassName", $classDefinitionString);

    //     $classDefinitionString = $this->getHeader($reflector) . "\r\n" . $classDefinitionString;

    //     return $classDefinitionString;
    // }




    // private function createInstance($className)
    // {


    //     $reflection = new \ReflectionClass($className);
    //     $construct  = $reflection->getConstructor();

    //     if ($construct) {

    //         $classParams     = $construct->getParameters();
    //         $passedParams    = [];

    //         foreach ($classParams as $key => $param) {

    //             $paramType = $param->getType();

    //             if ($paramType && (ltrim($paramType, "?") == self::class ||
    //                 ltrim($paramType, "?") == $this::class
    //             )) {

    //                 throw new \Exception("Not allowed to use " . self::class . " or " . $this::class . " in constructor");
    //             } else {

    //                 $paramName = $param->getName();
    //                 if (isset($arguments[$paramName])) {

    //                     $passedParams[] = $arguments[$paramName];
    //                 } elseif (isset($arguments[$key])) {

    //                     $passedParams[] = $arguments[$key];
    //                 } else {

    //                     if (count($this->meta['arguments']) === count($classParams)) {
    //                         $passedParams = $this->meta['arguments'];
    //                     } else {
    //                         throw new \Exception("Missing argument: $paramName at key: $key");
    //                     }
    //                 }
    //             }
    //         }

    //         if (!empty($passedParams)) {
    //             $instance = $reflection->newInstanceArgs($passedParams);
    //         } else {
    //             $instance = $reflection->newInstance();
    //         }
    //     } else {
    //         $instance = $reflection->newInstanceWithoutConstructor();
    //     }

    //     if (method_exists($instance, "setBox")) {
    //         call_user_func([$instance, "setBox"], $this->box);
    //     }

    //     $this->initMeta($reflection);

    //     return $instance;
    // }



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



    public function __call($name, $arguments)
    {

        if (!$this->instance) {
            return null;
        }

        if (method_exists($this->instance, $name)) {
            $reflectionMethod = new ReflectionMethod($this->instance, $name);
            $parameters = $reflectionMethod->getParameters();

            if (count($parameters) > 0) {
                $invocationArgs = [];

                foreach ($parameters as $key => $param) {


                    if (isset($arguments[$key])) {

                        $argument = $arguments[$key];

                        if ($param->hasType()) {
                            $paramType = $param->getType();
                            assert($paramType instanceof ReflectionNamedType);


                            error_log(strtolower(get_class($argument)) . "::" . strtolower($paramType->getName()));
                            // For class types, check if the argument is an instance of the parameter type
                            if (strtolower(get_class($argument)) == strtolower($paramType->getName())) {
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

        if (!isset($this->instance)) {
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
            "name"     => $modName ? $modName : ucfirst(basename($file)),
            "version"  => "1.0.0",
            "author"   => "Merapi panel",
            'image'     => "/src/views/assets/img/default-box.svg",
            "location" => str_replace("\\", "/", $file),
            "file"     => str_replace("\\", "/", $reflection->getFileName()),
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
            if (isset($array["image"]) && !empty($array["image"])) {
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


    public final function __toString()
    {

        if (method_exists($this->instance, "__toString")) {
            return $this->instance->__toString();
        }
        return "(Module)" . $this->className;
    }
}
