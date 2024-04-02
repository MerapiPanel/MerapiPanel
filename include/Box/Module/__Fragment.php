<?php
namespace MerapiPanel\Box\Module;

use MerapiPanel\Box\Module\Entity\Module;

abstract class __Fragment
{
    abstract function onCreate(Module $module);
}