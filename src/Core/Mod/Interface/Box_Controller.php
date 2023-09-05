<?php

namespace il4mb\Mpanel\Core\Mod\Interface;

use il4mb\Mpanel\Core\BoxMod;
use il4mb\Mpanel\Core\Http\Router;

interface Box_Controller {
    
    public function setBox(BoxMod $box);
    public function getBox(): ?BoxMod;
    public function register(Router $router);

}