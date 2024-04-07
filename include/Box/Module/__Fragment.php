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
    abstract function onCreate(Module $module);
}