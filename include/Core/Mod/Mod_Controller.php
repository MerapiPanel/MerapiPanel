<?php

namespace MerapiPanel\Core\Mod;

use MerapiPanel\Box;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Utility\Router;

abstract class Mod_Controller extends Module
{

    abstract function register(Router $router);
    
}