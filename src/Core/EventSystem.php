<?php

namespace il4mb\Mpanel\Core;

class EventSystem
{

    protected $listeners = [];

    /**
     * Registers a listener for a specific event.
     *
     * @param string $eventName The name of the event.
     * @param callable $listener The listener to be registered.
     */
    public function registerListener(string $eventName, callable $listener)
    {

        $this->listeners[$eventName][] = $listener;

    }



    
    /**
     * Fires an event and calls all registered listeners for that event.
     *
     * @param string $eventName The name of the event to fire.
     * @param array $payload An optional array of data to pass to the listeners.
     */
    public function fire(string $eventName, array $payload = [])
    {

        if (isset($this->listeners[$eventName])) 
        {

            foreach ($this->listeners[$eventName] as $listener) 
            {

                call_user_func_array($listener, $payload);

            }

        }

    }
    
}
