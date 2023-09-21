<?php

namespace MerapiPanel\Module\Panel\Api;

use MerapiPanel\Core\Abstract\Module;

class Admin extends Module
{

    public function Base()
    {
        return $this->service()->getBase();
    }

    public function ListMenu()
    {
        return $this->service()->getMenu();
    }
}
