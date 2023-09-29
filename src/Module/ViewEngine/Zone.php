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

        $fetchMode = $_SERVER["HTTP_SEC_FETCH_MODE"] ?? "navigate";

        [$module, $class, $method] = explode('_', $name);
        $classNames = "MerapiPanel\\Module\\" . ucfirst($module) . "\\Views\\" . ucfirst($class);

        $module = $this->box->$classNames();
        $module->setPayload($arguments[0]);

        $output = $module->$method();

        if (!isset($_GET['editor']) || (isset($_GET['editor']) && $_GET['editor'] != 1)) {

            return $output;

        } elseif (isset($_GET['editor']) && $_GET['editor'] == 1) {

            $params = $module->getPayload();
            $param = "";
            foreach ($params as $key => $value) {
                $param .= " $key=\"$value\"";
            }
            $output = "<div data-gjs-type=\"$name\" $param></div>";

            return $output;
        }
    }

    public function __toString()
    {
        return "(Module) ViewEngine";
    }
}
