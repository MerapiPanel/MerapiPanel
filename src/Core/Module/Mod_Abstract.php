<?php

namespace il4mb\Mpanel\Core\Module;

use il4mb\Mpanel\Core\AppBox;

abstract class Mod_Abstract 
{

    protected $attribute;
    final function __construct(){}

    public function handle($event)
    {

        


    }

    abstract function init(AppBox $box);
}