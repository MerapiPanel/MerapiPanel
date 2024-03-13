<?php

namespace MerapiPanel\Core;

use MerapiPanel\Box;
use MerapiPanel\Core\Abstract\Module;
use Throwable;

class Section
{

    protected string $name = '';

    /**
     * Constructor for the section.
     *
     * @param string $name description
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }



    public function getName(): string
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

        if ($method == "options") {
            return(Box::module("$moduleName")->service())->getOptions();
        }

        if (Box::module("$moduleName")->serviceIsExist("Views\\Api") && (Box::module("$moduleName")->service("Views\\Api"))->methodExists($method)) {
            return(Box::module("$moduleName")->service("Views\\Api"))->$method();
        }

        if (Box::module("$moduleName")->serviceIsExist("Api\\{$this->getName()}") && (Box::module("$moduleName")->service("Api\\{$this->getName()}"))->methodExists($method)) {
            return(Box::module("$moduleName")->service("Api\\{$this->getName()}"))->$method();
        }

        throw new \Exception("Property $method not found in module $moduleName");
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
