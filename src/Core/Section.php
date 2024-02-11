<?php

namespace MerapiPanel\Core;

use MerapiPanel\Box;

class Section
{

    protected $name = '';
    const EVENT_AFTER_CALL = 'after_call';

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



    public function getName()
    {
        return $this->name;
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

        [$module, $class, $method] = explode('_', $name);
        $classNames = "MerapiPanel\\Module\\" . ucfirst($module) . "\\template\\" . ucfirst($class);

        $moduleInstance = $this->getBox()->$classNames();

        if (!$moduleInstance) return null;
    }




    public function __toString()
    {
        return $this->name;
    }
}
