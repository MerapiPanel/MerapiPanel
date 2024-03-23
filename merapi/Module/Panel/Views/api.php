<?php

namespace MerapiPanel\Module\Panel\Views;

use MerapiPanel\Core\Abstract\Module;

class Api extends Module
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
