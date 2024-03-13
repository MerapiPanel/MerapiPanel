<?php

namespace MerapiPanel;

use Closure;
use MerapiPanel\Event\EventClient;
use MerapiPanel\Utility\Util;

class Event
{

    private static $instances = [];
    protected $listeners;
    protected $identify;

    public function __construct()
    {
        $this->listeners = [];
    }


    public function register($classRegister)
    {
        $reflector = new \ReflectionClass($classRegister);
        $methods = $reflector->getMethods(\ReflectionMethod::IS_STATIC);
        foreach ($methods as $method) {
            $name = $method->getName();
            $key = "merapipanel:" . preg_replace("/[^a-zA-Z0-9]+/im", ":", $name);
            $this->addListener($key, [$classRegister, $name]);
        }
    }

    public function addListener($key, $listener)
    {
        $this->listeners[$key][] = $listener;
    }


    public function notify($key, &$params = [])
    {

        if (is_array($key)) {
            $key = strtolower(implode(":", $key));
        }
        $key = preg_replace("/[^a-zA-Z0-9]+/im", ":", $key);
        $listener = $this->listeners[$key] ?? [];
        foreach ($listener as $callback) {
            $callback($params);
        }
    }


    private static function getClient()
    {
        $caller = false;
        foreach (debug_backtrace() as $call) {
            if (isset($call['file']) && str_replace((PHP_OS == "WINNT" ? "\\" : "/"), "/", $call['file']) != str_replace((PHP_OS == "WINNT" ? "\\" : "/"), "/", __FILE__)) {
                $caller = Util::getClassNameFromFile($call['file']);
                break;
            }
        }
        return $caller;
    }




    public static function on($client, $key, Closure $callback)
    {

        return EventClient::with($client)->addListener($key, $callback);

    }


    public static function fire($key, $params = [])
    {
        $caller = self::getClient();
        if ($caller)
            return EventClient::with($caller)->notify($key, $params);
    }
}
