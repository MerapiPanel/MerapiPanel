<?php

namespace MerapiPanel\Core\Abstract\Component;

use ArrayAccess;
use MerapiPanel\Core\Abstract\Module;


final class Options implements ArrayAccess
{

    const file = "options.json";
    protected $container = [];
    private $className;
    private static $instances = [];



    private function __construct($className)
    {
        $this->className = $className;
        $this->initialize();
    }



    public static function getInstance($className)
    {

        $moduleName = Module::getModuleName($className);
        $file = realpath(__DIR__ . "\\..\\..\\..\\Module") . "\\" . $moduleName;
        if (!file_exists($file)) {
            throw new \Exception("Module " . $moduleName . " not found");
        }


        if ($moduleName && !isset(self::$instances[$moduleName])) {
            self::$instances[$moduleName] = new self($className);
            return self::$instances[$moduleName];
        } else if ($moduleName && isset(self::$instances[$moduleName])) {
            return self::$instances[$moduleName];
        } else {
            throw new \Exception("Module $moduleName not found");
        }
    }





    /**
     * Initialize the configuration table and populate it with default values if necessary
     */
    private function initialize()
    {

        $file = realpath(__DIR__ . "\\..\\..\\..\\Module") . "\\" . Module::getModuleName($this->className);
        if (!file_exists($file)) {
            throw new \Exception("Module " . Module::getModuleName($this->className) . " not found, looking on " . $file);
        }
        $file .= "/" . self::file;
        if (!file_exists($file)) {
            file_put_contents($file, json_encode([], JSON_PRETTY_PRINT));
        }
        $this->container = json_decode(file_get_contents($file), true);


        // if (json_last_error() != JSON_ERROR_NONE) {
        //     rename($file, $file . ".old");
        //     file_put_contents($file, json_encode([], JSON_PRETTY_PRINT));
        //     $this->container = [];
        // }

        $container = [];
        foreach ($this->container as $key => $value) {
            $newKey = str_replace("-", "_", $key);
            $container[$newKey] = $value;
        }

        $this->container = $container;
       // $this->save();

    }








    function offsetExists(mixed $key): bool
    {
        $key = str_replace("-", "_", $key);
        return isset($this->container[$key]);
    }






    function offsetGet(mixed $key): mixed
    {
        $key = str_replace("-", "_", $key);
        return $this->container[$key];
    }





    function offsetSet($key, $value): void
    {
        $key = str_replace("-", "_", $key);
        $this->container[$key] = $value;
        $this->save();
    }







    function offsetUnset($key): void
    {
        unset($this->container[$key]);
        $this->save();
    }


    private function save()
    {
        $file = realpath(__DIR__ . "\\..\\..\\..\\Module") . "\\" . Module::getModuleName($this->className) . "\\" . self::file;
        file_put_contents($file, json_encode($this->container, JSON_PRETTY_PRINT));
    }
}
