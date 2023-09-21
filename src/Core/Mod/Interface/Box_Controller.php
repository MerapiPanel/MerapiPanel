<?php

namespace MerapiPanel\Mod\Interface;

use MerapiPanel\Box;
use MerapiPanel\Utility\Router;

interface Box_Controller {
    
    public function setBox(Box $box);
    public function getBox(): ?Box;
    public function register(Router $router);

}