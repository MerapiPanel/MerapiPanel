<?php

namespace Mp\Module\Panel\Api;

use Mp\Core\Abstract\Module;

class Guest extends Module
{

    public function navs()
    {
        return $this->service()->getNavs();
    }
}
