<?php

namespace il4mb\Mpanel\Core\Event;

class EventDispatcher 
{

    protected $listeners = [];

    public function addListener($eventName, $listener) 
    {

        $this->listeners[$eventName][] = $listener;
    }

    public function dispatch($eventName, $event) 
    {

        if (isset($this->listeners[$eventName])) 
        {
            foreach ($this->listeners[$eventName] as $listener) 
            {
                $listener->handle($event);
            }
        }
    }
}
