<?php

namespace il4mb\Mpanel\Core\Module;

use il4mb\Mpanel\Core\Container;
use il4mb\Mpanel\Core\Event\ObjectListener;

abstract class ModuleAbstract extends ObjectListener
{

    protected $attribute;
    final function __construct(){}

    public function handle($event)
    {

        


    }

    abstract function init(Container $container);
}