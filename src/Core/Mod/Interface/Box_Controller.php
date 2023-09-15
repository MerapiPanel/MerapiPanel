<?php

namespace Mp\Core\Mod\Interface;

use Mp\Core\BoxMod;
use Mp\Core\Http\Router;

interface Box_Controller {
    
    public function setBox(BoxMod $box);
    public function getBox(): ?BoxMod;
    public function register(Router $router);

}