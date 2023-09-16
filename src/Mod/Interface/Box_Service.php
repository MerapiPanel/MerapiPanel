<?php

namespace Mp\Mod\Interface;

use Mp\BoxMod;

interface Box_Controller {

    public function setBox(BoxMod $box);
    public function getBox(): ?BoxMod;
    
}