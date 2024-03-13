<?php

namespace MerapiPanel\Mod\Interface;

use MerapiPanel\Box;

interface Box_Controller {

    public function setBox(Box $box);
    public function getBox(): ?Box;
    
}