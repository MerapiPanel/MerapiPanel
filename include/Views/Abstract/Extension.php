<?php

namespace MerapiPanel\Views\Abstract;


use MerapiPanel\Views\View;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Class Extension
 * this is a base class for all extension
 * extension class should inherit this class, to make filter create a method with name begin with **fl_** or **fn_** for the function extension.
 * for option create doc comment with type \\* **@option** name value
 * @package MerapiPanel\Views\Abstract
 * @author  il4mb <https://github.com/il4mb>
 */
class Extension extends AbstractExtension
{
    protected View|null $view;

    public function __construct(View|null $view = null)
    {
        $this->view = $view;
    }



    protected function getView(): View|null
    {
        return $this->view;
    }

    public function getFunctions()
    {
        $extension_method = get_class_methods($this);

        return array_filter(array_map(function ($method) {
            if (strpos($method, "fn_") === 0) {
                $reflector = new \ReflectionMethod($this, $method);
                $options = $this->getOptionFromComment($reflector->getDocComment());
                return new TwigFunction(substr($method, 3), [$this, $method], $options);
            }
        }, $extension_method));
    }



    public function getFilters()
    {

        $extension_method = get_class_methods($this);

        return array_filter(array_map(function ($method) {
            if (strpos($method, "fl_") === 0) {
                $reflector = new \ReflectionMethod($this, $method);
                $options = $this->getOptionFromComment($reflector->getDocComment());
                return new TwigFilter(substr($method, 3), [$this, $method], $options);
            }
        }, $extension_method));
    }





    private function getOptionFromComment($commet)
    {

        if ($commet) {
            preg_match_all('/@option\s+([a-z0-9_]+)\s(.*)?/i', $commet, $matches);

            if (isset($matches[1], $matches[2])) {
                $options = array_combine($matches[1], $matches[2]);
                foreach ($options as $key => $value) {
                    if (str_contains($value, ",")) {
                        $options[$key] = array_values(array_filter(explode(",", $value), function ($value) {
                            return trim($value);
                        }));
                    }
                }
                return $options;
            }
        }
        return [];
    }
}