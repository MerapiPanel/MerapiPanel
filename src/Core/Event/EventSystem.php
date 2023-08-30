<?php

namespace il4mb\Mpanel\Core\Event;

class EventSystem
{

    protected $stack;

    public function __construct()
    {
        $this->stack = [];
    }

    public function on(string $event, callable $callback)
    {
        

    }


}