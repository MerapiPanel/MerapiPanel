<?php

namespace il4mb\Mpanel\Core\Event;

class ObjectListener
{

    

    public function __call($name, $arguments)
    {
        echo $name;
    }

}