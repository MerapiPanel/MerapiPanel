<?php

namespace Mp\Mod\Interface;

use Mp\Box;
use Mp\Module\Utility\Router;

interface Box_Controller {
    
    public function setBox(Box $box);
    public function getBox(): ?Box;
    public function register(Router $router);

}