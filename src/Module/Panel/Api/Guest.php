<?php

namespace MerapiQu\Module\Panel\Api;

use MerapiQu\Core\Abstract\Module;

class Guest extends Module
{

    public function navs()
    {
        return $this->service()->getNavs();
    }
}
