<?php

namespace MerapiPanel\Event;

use Closure;

class EventClient
{
    private static $instances = [];
    private $className;
    private $pool = [];


    public function __construct($ownerClassName)
    {
        $this->className = $ownerClassName;
    }


    public function addListener($key, Closure $callback)
    {
        $this->pool[$key][] = $callback;
    }

    public function notify($key, &$params = [])
    {
        $callbacks = $this->pool[$key] ?? [];
        foreach ($callbacks as $callback) {
            $callback(...$params);
        }
    }


    public static function with($ownerClassName)
    {

        if (!isset(self::$instances[$ownerClassName])) {
            self::$instances[$ownerClassName] = new self($ownerClassName);
        }

        return self::$instances[$ownerClassName];
    }


    public function __toString()
    {
        $data = [
            "className" => $this->className,
            "pool" => $this->pool
        ];

        return json_encode($data, JSON_PRETTY_PRINT);
    }
}
