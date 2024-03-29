<?php

namespace MerapiPanel\Core\Abstract\Component;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use MerapiPanel\Core\Abstract\Module;
use Traversable;


final class Options implements ArrayAccess, IteratorAggregate, Countable
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

        $container = [];
        foreach ($this->container as $key => $value) {
            $key = preg_replace("/\-+|\s+/", "_", $key);
            if (gettype($value) == "array" || gettype($value) == "object") {
                $container[$key] = new Nested($this, $value);
            } else {
                $container[$key] = $value;
            }
        }

        $this->container = $container;
    }


    function count(): int
    {
        return count($this->container);
    }


    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->container);
    }


    function offsetExists(mixed $offset): bool
    {
        $key = preg_replace("/\-+|\s+/", "_", $offset);
        return isset($this->container[$key]);
    }






    function offsetGet(mixed $offset): mixed
    {
        $key = preg_replace("/\-+|\s+/", "_", $offset);
        return $this->container[$key];
    }





    function offsetSet($offset, $value): void
    {
        $key = preg_replace("/\-+|\s+/", "_", $offset);
        if (is_array($value) || is_object($value)) {
            $this->container[$key] = new Nested($this, $value);
        } else {
            $this->container[$key] = $value;
        }

        $this->save();
    }







    function offsetUnset($offset): void
    {

        $key = preg_replace("/\-+|\s+/", "_", $offset);
        unset($this->container[$key]);
        $this->save();
    }


    public function save()
    {
        $file = realpath(__DIR__ . "\\..\\..\\..\\Module") . "\\" . Module::getModuleName($this->className) . "\\" . self::file;
        file_put_contents($file, json_encode($this->toArray(), JSON_PRETTY_PRINT));
    }

    public function toArray()
    {
        $stack = [];

        foreach ($this->container as $key => $value) {
            if (gettype($value) == "array" || gettype($value) == "object") {
                $stack[$key] = $value->toArray();
            } else {
                $stack[$key] = $value;
            }
        }

        return $stack;
    }
}


final class Nested implements ArrayAccess, IteratorAggregate, Countable
{

    private Options $options;
    private $container = [];


    public function __construct(Options $options, $value)
    {
        $this->options = $options;
        $this->container = $value;

        foreach ($this->container as $key => $value) {
            $key = preg_replace("/\-+|\s+/", "_", $key);
            if (gettype($value) == "array" || gettype($value) == "object") {
                $this->container[$key] = new Nested($this->options, $value);
            } else {
                $this->container[$key] = $value;
            }
        }
    }

    function count(): int
    {
        return count($this->container);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->container);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $key = preg_replace("/\-+|\s+/", "_", $offset);
        if (is_array($value) || is_object($value)) {
            $this->container[$key] = new Nested($this->options, $value);
        } else {
            $this->container[$key] = $value;
        }

        $this->options->save();
    }

    public function offsetExists(mixed $offset): bool
    {
        $key = preg_replace("/\-+|\s+/", "_", $offset);
        return isset($this->container[$key]);
    }

    public function offsetUnset(mixed $offset): void
    {
        $key = preg_replace("/\-+|\s+/", "_", $offset);
        unset($this->container[$key]);
        $this->options->save();
    }

    public function offsetGet(mixed $offset): mixed
    {
        $key = preg_replace("/\-+|\s+/", "_", $offset);
        return $this->container[$key];
    }


    public function toArray()
    {
        $stack = [];
        foreach ($this->container as $key => $value) {
            if ($value instanceof Nested) {
                $stack[$key] = $value->toArray();
            } else {
                $stack[$key] = $value;
            }
        }

        return $stack;
    }
}
