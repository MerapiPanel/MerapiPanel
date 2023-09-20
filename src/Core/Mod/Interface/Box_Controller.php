<?php

namespace MerapiQu\Mod\Interface;

use MerapiQu\Box;
use MerapiQu\Utility\Router;

interface Box_Controller {
    
    public function setBox(Box $box);
    public function getBox(): ?Box;
    public function register(Router $router);

}