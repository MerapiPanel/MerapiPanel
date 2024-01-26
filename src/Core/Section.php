<?php

namespace MerapiPanel\Core;

use MerapiPanel\Box;

class Section
{

    protected $name = '';

    /**
     * Constructor for the section.
     *
     * @param string $name description
     */
    public function __construct($name)
    {
        $this->name = $name;
    }



    /**
     * Get the Box object.
     *
     * @return Box
     */
    private function getBox(): Box
    {
        return Box::Get($this);
    }



    /**
     * Magic method to retrieve a property.
     *
     * @param string $name The name of the property to retrieve.
     * @return mixed
     */
    public function __get($name)
    {
        // Split the property name into parts and remove any empty values
        $parts = array_values(array_filter(explode('_', $name)));
        // Get the module name from the first part and remove it from the parts array
        $module = $parts[0];
        unset($parts[0]);
        // Construct the module and method names
        $method = implode('_', $parts);
        $module = "MerapiPanel\\Module\\" . ucfirst($module) .  "\\Api\\" . ucfirst($this);
        // Get the instance of the module and call the method
        $instance = $this->getBox()->$module();
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

        $module = $this->getBox()->$classNames();
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
        return $this->name;
    }
}
