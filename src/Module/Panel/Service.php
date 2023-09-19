<?php

namespace Mp\Module\Panel;

use Mp\Core\Abstract\Module;

class Service extends Module {

    protected $box;

    public function setBox($box) {
        $this->box = $box;
    }

    public function hallo() {
        echo "Hallo";
    }

    
}
