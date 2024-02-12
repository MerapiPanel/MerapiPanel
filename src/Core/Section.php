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
        
        $exploded = array_values(array_filter(explode('_', $name)));
        $moduleName = $exploded[0];
        unset($exploded[0]);
        // Get the module name from the first part and remove it from the parts array
        $method = implode('_', $exploded);
        // Construct the module and method names
        return (Box::module("$moduleName")->service("Api\\{$this->getName()}"))->$method();
    }


    public function __isset($name)
    {
        return true;
    }


    public function __toString()
    {
        return $this->name;
    }
}
