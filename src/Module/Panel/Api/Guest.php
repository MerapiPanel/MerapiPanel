<?php

namespace MerapiPanel\Module\Panel\Api;

use MerapiPanel\Core\Abstract\Module;

class Guest extends Module
{

    public function ListMenu()
    {
        return $this->service()->getMenu();
    }
}
