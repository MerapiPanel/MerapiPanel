<?php

namespace MerapiPanel\Module\ViewEngine;

use MerapiPanel\Box;

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

        $parts = array_values(array_filter(explode('_', $name)));
        $module = $parts[0];
        unset($parts[0]);

        $method = implode('_', $parts);

        $module = "MerapiPanel\\Module\\" . ucfirst($module) .  "\\Api\\" . ucfirst($this->zone);

        $instance = $this->box->$module();
        return $instance->$method();
    }


    public function __isset($name)
    {
        return true;
    }



    public function __call($name, $arguments)
    {

        [$module, $class, $method] = explode('_', $name);
        $classNames = "MerapiPanel\\Module\\" . ucfirst($module) . "\\Views\\" . ucfirst($class);
        $module = $this->box->$classNames();
        $module->setPayload($arguments[0]);

        return $module->$method();
    }

    public function __toString()
    {
        return "(Module) ViewEngine";
    }
}
