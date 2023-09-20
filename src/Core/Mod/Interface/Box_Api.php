<?php

namespace MerapiQu\Mod\Interface;

use MerapiQu\Box;

interface Box_Api {

    public function setBox(Box $box);
    public function getBox(): ?Box;
    
}