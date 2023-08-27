<?php

namespace il4mb\Mpanel\Modules;

use il4mb\Mpanel\Core\Event\EventListenerInterface;

abstract class ModuleAbstract implements EventListenerInterface {

    protected $eventDispatcher;

    public function __construct($eventDispatcher) 
    {

        $this->eventDispatcher = $eventDispatcher;
        $this->eventDispatcher->addListener('user.registered', $this);
        $this->eventDispatcher->addListener('order.placed', $this);

    }


    public function init() 
    {

    }


   


    public function handle($event) 
    {


        
    }
    
}