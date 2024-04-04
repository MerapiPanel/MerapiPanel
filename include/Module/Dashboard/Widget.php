<?php

namespace MerapiPanel\Module\Dashboard;

use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Core\Abstract\Module;

class Widget extends __Fragment
{

    protected $module;

    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
    {
        $this->module = $module;
    }

    public function scanProvideWidgets()
    {

        $classNames = [];
        $dirs = glob(realpath(__DIR__ . "/../") . "/**/Views/Widget.php", GLOB_NOSORT);
        foreach ($dirs as $dir) {

            $namespace = str_replace(realpath(__DIR__ . "/../"), "", str_replace("/Views/Widget.php", "", $dir));
            $className = "\\MerapiPanel\\Module\\" . trim($namespace, "\\/") . "\\Views\\Widget";

            if (class_exists($className)) {
                $classNames[] = $className;
            }
        }

        return $classNames;
    }



    public function getDefindedWidgets()
    {
        $classNames = $this->scanProvideWidgets();
        $widgets = [];
        foreach ($classNames as $className) {

            $moduleName = Module::getModuleName($className);
            $reflector = new \ReflectionClass($className);
            $methods = $reflector->getMethods(\ReflectionMethod::IS_FINAL);
            foreach ($methods as $method) {

                $doc = self::extractDoc($method->getDocComment());
                $widgets[] = array_merge($doc, [
                    "name" => strtolower($moduleName . ":" . $method->getName()),
                    "category" => $moduleName,
                ]);
            }
        }

        return $widgets;
    }




    public function renderWidget($name)
    {

        list($module, $method) = explode(":", $name);
        $className = "\\MerapiPanel\\Module\\" . ucfirst($module) . "\\Views\\Widget";

        if (class_exists($className)) {
            $reflector = new \ReflectionClass($className);
            $instance = $reflector->newInstance();

            return [
                "code" => 200,
                "message" => "success",
                "data" => $instance->{$method}()
            ];

        }

        return [
            "code" => 400,
            "message" => "fail to find widget: " . $name,
        ];
    }


    private static function extractDoc($comment)
    {

        $docs = [
            "title" => "No title",
            "icon" => "",
        ];
        $pattern = '/\@(.*?)\s(.*)/';

        if (preg_match_all($pattern, $comment, $matches)) {
            foreach ($matches[1] as $key => $value) {
                $docs[$value] = trim($matches[2][$key]);
            }
        }
        return $docs;
    }
}