<?php

namespace MerapiPanel\Box\Module;

use MerapiPanel\Box\Module\Entity\Module;

/**
 * Description: Module Abstract Fragment.
 * @author      ilham b <durianbohong@gmail.com>
 * @copyright   Copyright (c) 2022 MerapiPanel
 * @license     https://github.com/MerapiPanel/MerapiPanel/blob/main/LICENSE
 * @lastUpdate  2024-02-10
 */
abstract class __Fragment
{
    /**
     * Summary of onCreate
     * this is api method of module fragment 
     * onCreate will call while fragment is created, this method only have one parameter, params is the module witchis is its self
     * @param Module $module
     * @return void
     */
    abstract function onCreate(Module $module);

    /**
     * Summary of onInit
     * implement this on Service of module
     * onInit will trigger on post load 
     * use this for preparing modules
     * @return void
     */
    function onInit() { }
}
