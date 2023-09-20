<?php

namespace MerapiQu\Module\ViewEngine;

use MerapiQu\Box;

class Zone
{

    protected $box;
    protected $zone;

    public function __construct($zone)
    {
        $this->zone = $zone;
    }

    final function setBox(?Box $box)
    {
        $this->box = $box;
    }

    final function getBox(): ?Box
    {
        return $this->box;
    }




    public function __get($name)
    {

        [$module, $method] = array_values(array_filter(explode('_', $name)));
        $module = "MerapiQu\\Module\\" . ucfirst($module) . "\\Api\\" . ucfirst($this->zone);

        $instance = $this->box->$module();
        return $instance->$method();
    }
    public function __isset($name)
    {
        return true;
    }

    public function __toString()
    {
        return "(Module) ViewEngine";
    }
}
