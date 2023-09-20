<?php

namespace MerapiQu\Mod\Interface;

use MerapiQu\Box;

interface Box_Controller {

    public function setBox(Box $box);
    public function getBox(): ?Box;
    
}