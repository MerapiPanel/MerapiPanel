<?php

namespace MerapiPanel;

class Event
{

    protected $listeners;

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
        foreach ($listener AS $callback) {
          $params = $callback($params);
        }

        return $params;
    }

}
