<?php

namespace il4mb\Mpanel\Core\Mod\Interface;

use il4mb\Mpanel\Core\BoxMod;

interface Box_Controller {

    public function setBox(BoxMod $box);
    public function getBox(): ?BoxMod;
    
}