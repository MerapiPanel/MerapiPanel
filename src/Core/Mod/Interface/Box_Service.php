<?php

namespace Mp\Core\Mod\Interface;

use Mp\Core\BoxMod;

interface Box_Controller {

    public function setBox(BoxMod $box);
    public function getBox(): ?BoxMod;
    
}