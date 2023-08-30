<?php

namespace il4mb\Mpanel\Core;

use il4mb\Mpanel\Core\Event\EventSystem;

abstract class BoxAbstract
{

    protected Config $cog;
    protected EventSystem $eventListener;

    final public function setConfig(string $fileYml) 
    {
        $this->cog = new Config($fileYml);
    }

    final public function getConfig(): Config
    {
        return $this->cog;
    }


    final function __call($name, $arguments)
    {
        print_r($name);
    }

    final function __invoke($arguments)
    {

        print_r($arguments);
        
    }
}
