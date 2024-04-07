<?php

namespace MerapiPanel\Box\Module;

use MerapiPanel\Box\Module\__Fragment;

/**
 * Description: Module Abstract Controller.
 * @author      ilham b <durianbohong@gmail.com>
 * @copyright   Copyright (c) 2022 MerapiPanel
 * @license     https://github.com/MerapiPanel/MerapiPanel/blob/main/LICENSE
 * @lastUpdate  2024-02-10
 */
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