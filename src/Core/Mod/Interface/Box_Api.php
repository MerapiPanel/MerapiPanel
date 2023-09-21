<?php

namespace MerapiPanel\Mod\Interface;

use MerapiPanel\Box;

interface Box_Api {

    public function setBox(Box $box);
    public function getBox(): ?Box;
    
}