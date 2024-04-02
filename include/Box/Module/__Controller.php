<?php

namespace MerapiPanel\Box\Module;

use MerapiPanel\Box\Module\__Fragment;

abstract class __Controller extends __Fragment
{

    protected $module;
    public final function onCreate(Entity\Module $module)
    {
        $this->module = $module;
    }

    protected function getModule(): Entity\Module
    {
        return $this->module;
    }

    abstract function register();
}