<?php
namespace MerapiPanel\Module\User\Views\Admin;

use MerapiPanel\Box\Module\__Fragment;

class Api extends __Fragment
{

    protected $module;
    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
    {
        $this->module = $module;
    }


    public function fetchAll()
    {
        return $this->module->fetchAll();
    }
}