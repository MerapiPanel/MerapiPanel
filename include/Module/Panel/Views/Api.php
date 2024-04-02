<?php

namespace MerapiPanel\Module\Panel\Views;

use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Core\Abstract\Module;

class Api extends __Fragment
{
    protected $module;
    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
    {
        $this->module = $module;
    }


    public function Base()
    {
        return $this->module->getBase();
    }

    public function ListMenu()
    {
        return $this->module->getMenu();
    }
}
