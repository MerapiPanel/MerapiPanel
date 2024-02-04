<?php

namespace MerapiPanel\Core;

use MerapiPanel\Box;
use MerapiPanel\Core\View\Abstract\ViewComponent;

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
        [$module, $class, $method] = explode('_', $name);
        $classNames = "MerapiPanel\\Module\\" . ucfirst($module) . "\\template\\" . ucfirst($class);
        $moduleInstance = $this->getBox()->$classNames();

        if ($moduleInstance->getInstance() instanceof ViewComponent) {
            $moduleInstance->setPayload($arguments[0]);
            $output = $moduleInstance->$method();

            if (isset($_SERVER['HTTP_TEMPLATE_EDIT']) && $_SERVER['HTTP_TEMPLATE_EDIT'] == 'initial') {
                $controller = $this->getBox()->Utility_router()->getProperty('controller');
                $controllerClass  = $controller['class'] ?? null;
                $controllerMethod = $controller['method'] ?? null;

                if ($controllerClass == "MerapiPanel\\Module\\Editor\\Controller\\Admin") {
                    $params = $moduleInstance->getPayload();
                    $paramString = "";
                    foreach ($params as $key => $value) {
                        $paramString .= " $key=\"$value\"";
                    }
                    $output = "<div data-gjs-type=\"$name\" $paramString></div>";
                }
            }

            return $output;
        }
    }




    public function __toString()
    {
        return $this->name;
    }
}
