<?php

namespace il4mb\Mpanel\Core;

use Exception;

use function PHPSTORM_META\type;

class Container
{

    protected $parts = [];
    protected $directory = __DIR__ . "/..";

    public function __construct()
    {
    }


    public function register(string $class, ...$args)
    {

        if (class_exists($class)) {

            $this->parts[$class] = new $class($args);
        } else {

            throw new Exception("Class $class does not exist");
        }
    }


    public function __call($name, $args)
    {

        $instance = false;

        if (!isset($this->parts[$name])) {

            foreach ($this->parts as $key => $part) {

                if (strpos(basename($key), $name) !== 0) 
                {
                    $instance = $part;
                }
            }
        } else {

            $instance = $this->parts[$name];
        }

        if ($instance) 
        {

            return $instance;
        } else {
            throw new Exception("Class $name does not exist");
        }
    }
}
