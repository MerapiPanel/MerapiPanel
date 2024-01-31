<?php

namespace MerapiPanel\Mod\Interface;

use MerapiPanel\Box;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Utility\Router;

abstract class Box_Controller extends Module
{

    abstract function register(Router $router);
    
}