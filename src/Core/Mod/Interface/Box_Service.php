<?php

namespace Mp\Mod\Interface;

use Mp\Box;

interface Box_Controller {

    public function setBox(Box $box);
    public function getBox(): ?Box;
    
}