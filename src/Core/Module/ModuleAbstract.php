<?php

namespace il4mb\Mpanel\Core\Module;

use il4mb\Mpanel\Core\Event\EventListenerInterface;

abstract class ModuleAbstract implements EventListenerInterface
{

    protected $attribute;
    final function __construct(){}

    public function handle($event)
    {

    }

    abstract function init();
}