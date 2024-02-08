<?php

namespace MerapiPanel;

use Closure;
use MerapiPanel\Event\EventClient;
use MerapiPanel\Event\EventOwner;
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


    public function addListener($key, $listener)
    {
        $this->listeners[$key][] = $listener;
    }


    public function notify($key, &$params = [])
    {

        $listener = $this->listeners[$key] ?? [];
        foreach ($listener as $callback) {
            $params = $callback($params);
        }

        return $params;
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
        if ($caller) return EventClient::with($caller)->notify($key, $params);
    }
}
