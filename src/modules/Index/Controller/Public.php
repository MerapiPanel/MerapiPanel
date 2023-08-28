<?php

namespace il4mb\Mpanel\Modules;

use il4mb\Mpanel\Core\Container;
use il4mb\Mpanel\Core\Module\ModuleAbstract;

class IndexController extends ModuleAbstract
{
    
    private $container;
    
    public function init(Container $container)
    {
        $this->container = $container;
        
    }
}