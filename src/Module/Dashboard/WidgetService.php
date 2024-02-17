<?php

namespace MerapiPanel\Module\Dashboard;

use MerapiPanel\Core\Abstract\Module;

class WidgetService
{

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
                    "name" => strtolower($reflector->getShortName() . ":" . $method->getName()),
                    "category" => $moduleName,
                ]);
            }
        }

        return $widgets;
    }


    private static function extractDoc($comment)
    {

        $docs = [
            "title" => "No title",
            "icon"  => "",
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