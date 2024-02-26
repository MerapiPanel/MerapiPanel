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


        if (json_last_error() != JSON_ERROR_NONE) {
            file_put_contents($file, json_encode([], JSON_PRETTY_PRINT));
            $this->container = [];
        }
    }







    private function getDefaultConfig(): array|false
    {
        $file = realpath(__DIR__ . "\\..\\..\\..\\Module") . "\\" . Module::getModuleName($this->className);
        if (!file_exists($file)) {
            throw new \Exception("Module " . Module::getModuleName($this->className) . " not found, looking on " . $file);
        }
        return json_decode(file_get_contents($file), true);
    }








    private function getCaller()
    {
        $file = false;
        foreach (debug_backtrace() as $call) {
            if (isset($call['file']) && in_array("module", array_map("strtolower", explode((PHP_OS == "WINNT" ? "\\" : "/"), $call['file'])))) {
                $file = $call['file'];
                break;
            }
        }
        return $file;
    }








    private function getModuleName()
    {
        $caller   = $this->getCaller();
        $dirname  = dirname($caller);
        $basename = basename($dirname);

        while (strtolower(basename($dirname)) != "module" && $dirname != '/' && $dirname != null) {

            $basename = basename($dirname);
            $dirname = realpath("$dirname/..");

            if (preg_replace('/[^a-zA-Z0-9]+/', '', $_SERVER['DOCUMENT_ROOT']) == preg_replace('/[^a-zA-Z0-9]+/', '', $dirname)) {
                $basename = null;
                break;
            }
        }

        return $basename;
    }








    function offsetExists(mixed $key): bool
    {
        return isset($this->container[$key]);
    }






    function offsetGet(mixed $key): mixed
    {
        return $this->container[$key];
    }





    function offsetSet($key, $value): void
    {
        $this->container[$key] = $value;
        $this->save();
    }







    function offsetUnset($value): void
    {
        unset($this->container[$value]);
        $this->save();
    }


    private function save()
    {
        $file = __DIR__ . "/../../../Module" . Module::getModuleName($this->className);
        file_put_contents($file, json_encode($this->container, JSON_PRETTY_PRINT));
    }
}
