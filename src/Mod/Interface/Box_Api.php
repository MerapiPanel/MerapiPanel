<?php

namespace Mp\Mod\Interface;

use Mp\BoxMod;

interface Box_Api {

    public function setBox(BoxMod $box);
    public function getBox(): ?BoxMod;
    
}