<?php

namespace Mp\Mod\Interface;

use Mp\BoxMod;
use Mp\Utility\Router;

interface Box_Controller {
    
    public function setBox(BoxMod $box);
    public function getBox(): ?BoxMod;
    public function register(Router $router);

}